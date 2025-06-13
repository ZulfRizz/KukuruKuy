<?php

namespace App\Http\Controllers\Stok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;

class StockController extends Controller {
    /**
     * Menampilkan halaman manajemen stok untuk cabang pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        // Dapatkan user yang sedang login
        $user = Auth::user();
        $franchiseId = $user->franchise_id;

        // Jika user tidak punya cabang, tampilkan halaman error atau pesan kosong
        if (!$franchiseId) {
            // Anda bisa membuat view khusus untuk ini
            return view('stok.no_franchise');
        }

        // Ambil semua data stok HANYA untuk cabang tempat user bekerja
        // Gunakan `with('ingredient')` (Eager Loading) untuk mengambil data relasi
        // secara efisien dan menghindari N+1 problem.
        $stocks = Stock::where('franchise_id', $franchiseId)
            ->with('ingredient')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data stok ke view 'stok.index'
        return view('stok.index', [
            'stocks' => $stocks,
            'franchiseName' => $user->franchise->name, // Ambil nama cabang dari relasi
        ]);
    }
}
