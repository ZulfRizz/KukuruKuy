<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Franchise extends Model {
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name',
        'address',
        'phone_number',
    ];

    /**
     * Mendefinisikan relasi: Satu Franchise (cabang) memiliki banyak User (pengguna/karyawan).
     */
    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    /**
     * Mendefinisikan relasi: Satu Franchise (cabang) memiliki banyak data Stok.
     */
    public function stocks(): HasMany {
        return $this->hasMany(Stock::class);
    }

    /**
     * Mendefinisikan relasi: Satu Franchise (cabang) memiliki banyak Order (pesanan).
     */
    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }
}
