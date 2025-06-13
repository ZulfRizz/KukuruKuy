<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'invoice_number',
        'franchise_id',
        'user_id',
        'total_amount',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Mendefinisikan relasi: Pesanan ini dilakukan di satu Franchise.
     */
    public function franchise(): BelongsTo {
        return $this->belongsTo(Franchise::class);
    }

    /**
     * Mendefinisikan relasi: Pesanan ini dibuat oleh satu User (kasir).
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi: Satu Pesanan memiliki banyak Detail Pesanan (item).
     */
    public function details(): HasMany {
        return $this->hasMany(OrderDetail::class);
    }
}
