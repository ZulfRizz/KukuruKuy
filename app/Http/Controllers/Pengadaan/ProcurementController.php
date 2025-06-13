<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Procurement;
use App\Models\Ingredient;

class ProcurementController extends Controller {
    /**
     * Menampilkan halaman pengajuan pengadaan barang.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        $user = Auth::user();
        $franchiseId = $user->franchise_id;

        // Ambil riwayat pengajuan dari cabang ini
        $procurements = Procurement::where('franchise_id', $franchiseId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil daftar bahan baku untuk ditampilkan di form pengajuan
        $ingredients = Ingredient::orderBy('name')->get();

        return view('pengadaan.index', [
            'procurements' => $procurements,
            'ingredients' => $ingredients,
            'franchiseName' => $user->franchise->name,
        ]);
    }

    /**
     * Menyimpan permintaan pengadaan barang baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $validated = $request->validate([
            // Validasi untuk form pengajuan, misal:
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $franchiseId = $user->franchise_id;

        // Buat record Procurement utama
        $procurement = Procurement::create([
            'franchise_id' => $franchiseId,
            'user_id' => $user->id,
            'status' => 'pending', // Status awal: menunggu persetujuan
            'notes' => $validated['notes'] ?? null,
        ]);

        // Simpan detail item yang diminta
        foreach ($validated['items'] as $item) {
            $procurement->details()->create([
                'ingredient_id' => $item['ingredient_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('pengadaan.index')->with('success', 'Permintaan pengadaan berhasil dikirim!');
    }
}
