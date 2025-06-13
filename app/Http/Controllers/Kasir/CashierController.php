<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\Stock;
use App\Events\NewOrderCreated; // Event untuk notifikasi real-time
use Illuminate\Validation\ValidationException;

class CashierController extends Controller {
    /**
     * Menampilkan halaman utama kasir (Point of Sale).
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        // Ambil semua produk yang aktif untuk ditampilkan di menu
        $products = Product::orderBy('name')->get();

        // Ambil data user yang sedang login (kasir)
        $cashier = Auth::user();

        // Tampilkan view 'kasir.index' dan kirim data produk & kasir ke sana
        return view('kasir.index', [
            'products' => $products,
            'cashier' => $cashier,
        ]);
    }

    /**
     * Memproses dan menyimpan pesanan baru dari kasir.
     * Ini akan diakses melalui AJAX/Fetch dari JavaScript di halaman kasir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrder(Request $request) {
        // 1. Validasi Input dari Frontend
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        // 2. Dapatkan informasi kasir dan cabangnya
        $cashier = Auth::user();
        $franchiseId = $cashier->franchise_id;

        // Jika kasir tidak terikat ke cabang manapun, tolak transaksi
        if (!$franchiseId) {
            return response()->json(['message' => 'Error: Pengguna tidak terikat pada cabang manapun.'], 403);
        }

        // 3. Gunakan Database Transaction
        // Ini SANGAT PENTING! Jika salah satu proses gagal (misal stok kurang),
        // semua proses yang sudah berjalan akan dibatalkan (rollback).
        // Ini menjaga data tetap konsisten.
        try {
            $order = DB::transaction(function () use ($validated, $cashier, $franchiseId) {
                // Ambil ID produk dari keranjang untuk mengambil data harga dari DB
                $productIds = collect($validated['cart'])->pluck('id');
                $products = Product::find($productIds);

                // Hitung total harga di backend untuk keamanan (hindari manipulasi dari client)
                $totalAmount = collect($validated['cart'])->sum(function ($item) use ($products) {
                    $product = $products->find($item['id']);
                    return $product->price * $item['quantity'];
                });

                // Buat record Order baru
                $order = Order::create([
                    'invoice_number' => 'INV-' . time() . '-' . $franchiseId, // Contoh format invoice
                    'franchise_id' => $franchiseId,
                    'user_id' => $cashier->id,
                    'total_amount' => $totalAmount,
                ]);

                // Loop setiap item di keranjang untuk disimpan ke order_details dan mengurangi stok
                foreach ($validated['cart'] as $item) {
                    $product = $products->find($item['id']);

                    // Buat record Order Detail
                    $order->details()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                    ]);

                    // Kurangi stok bahan baku berdasarkan resep produk
                    // Eager load resep (ingredients) untuk efisiensi
                    $product->load('ingredients');

                    foreach ($product->ingredients as $ingredient) {
                        // $ingredient->pivot->quantity adalah jumlah bahan baku per 1 porsi produk
                        $quantityToReduce = $ingredient->pivot->quantity * $item['quantity'];

                        // Cari stok bahan baku ini di cabang saat ini
                        $stock = Stock::where('franchise_id', $franchiseId)
                            ->where('ingredient_id', $ingredient->id)
                            ->first();

                        // Validasi stok
                        if (!$stock || $stock->quantity < $quantityToReduce) {
                            // Jika stok tidak ada atau tidak cukup, batalkan transaksi
                            throw ValidationException::withMessages([
                                'stock' => 'Stok untuk bahan baku "' . $ingredient->name . '" tidak mencukupi!'
                            ]);
                        }

                        // Kurangi stok
                        $stock->decrement('quantity', $quantityToReduce);
                    }
                }

                return $order;
            });

            // 4. Kirim Event Notifikasi Real-time
            // Jika transaksi berhasil, panggil event NewOrderCreated
            NewOrderCreated::dispatch($order);

            // 5. Beri Respon Sukses
            return response()->json([
                'message' => 'Pesanan berhasil dibuat!',
                'invoice' => $order->invoice_number,
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            // Tangkap error validasi (misal: stok tidak cukup)
            return response()->json(['message' => $e->getMessage()], 422); // 422 Unprocessable Entity
        } catch (\Throwable $th) {
            // Tangkap error lainnya yang mungkin terjadi
            report($th); // Laporkan error ke log
            return response()->json(['message' => 'Terjadi kesalahan internal. Silakan coba lagi.'], 500);
        }
    }
}
