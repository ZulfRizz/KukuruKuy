<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model {
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Mendefinisikan relasi many-to-many: Satu Produk terdiri dari banyak Ingredient (bahan baku).
     * `withPivot` penting untuk mengambil data tambahan (jumlah) dari tabel pivot.
     */
    public function ingredients(): BelongsToMany {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
            ->withPivot('quantity', 'unit');
    }
}
