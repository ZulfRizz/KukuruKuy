<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Procurement;
// use App\Models\Ingredient; // Tidak perlu karena kita tidak menampilkannya di sini

class ProcurementController extends Controller {
    /**
     * Menyimpan permintaan pengadaan barang baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $validated = $request->validate([
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

        // === BAGIAN YANG DIPERBAIKI ===
        // Arahkan kembali ke dasbor stok yang benar (stok.index), bukan pengadaan.index
        return redirect()->route('stok.index')->with('success', 'Permintaan pengadaan berhasil dikirim!');
    }
}
