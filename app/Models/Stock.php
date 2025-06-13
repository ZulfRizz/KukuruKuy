<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model {
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'franchise_id',
        'ingredient_id',
        'quantity',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    /**
     * Mendefinisikan relasi: Data Stok ini milik satu Franchise.
     */
    public function franchise(): BelongsTo {
        return $this->belongsTo(Franchise::class);
    }

    /**
     * Mendefinisikan relasi: Data Stok ini merujuk pada satu Ingredient.
     */
    public function ingredient(): BelongsTo {
        return $this->belongsTo(Ingredient::class);
    }
}
