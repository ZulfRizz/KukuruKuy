<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model {
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * Menonaktifkan timestamps (created_at, updated_at) untuk model ini.
     */
    public $timestamps = false;

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Mendefinisikan relasi: Detail ini milik satu Order utama.
     */
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mendefinisikan relasi: Detail ini merujuk pada satu Product.
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
