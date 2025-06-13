<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model {
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name',
        'unit',
    ];

    /**
     * Mendefinisikan relasi many-to-many: Satu Ingredient bisa digunakan di banyak Product.
     */
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_ingredient')->withPivot(
            'quantity',
            'unit');
    }
}
