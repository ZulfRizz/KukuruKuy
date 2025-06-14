<?php

namespace App\Http\Controllers\Stok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Procurement;
use App\Models\Ingredient;

class StockController extends Controller {
    /**
     * Menampilkan dasbor stok gabungan (melihat stok & pengadaan).
     */
    public function index() {
        $user = Auth::user();
        $franchiseId = $user->franchise_id;

        if (!$franchiseId) {
            return view('stok.no_franchise');
        }

        // Ambil data stok untuk cabang ini
        $stocks = Stock::where('franchise_id', $franchiseId)
            ->with('ingredient')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil riwayat pengajuan dari cabang ini
        $procurements = Procurement::where('franchise_id', $franchiseId)
            ->with('user')
            ->latest() // Mengurutkan dari yang terbaru
            ->take(10) // Ambil 10 riwayat terakhir
            ->get();

        // Ambil daftar semua bahan baku untuk form permintaan
        $ingredients = Ingredient::orderBy('name')->get();

        // Kirim semua data yang dibutuhkan ke satu view
        return view('stok.index', [
            'stocks' => $stocks,
            'procurements' => $procurements,
            'ingredients' => $ingredients,
            'franchiseName' => $user->franchise->name,
        ]);
    }
}
