<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements MustVerifyEmail {
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'franchise_id',
        'role',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendapatkan atribut yang seharusnya di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendefinisikan relasi: Seorang User (pengguna) dimiliki oleh satu Franchise (cabang).
     */
    public function franchise(): BelongsTo {
        return $this->belongsTo(Franchise::class);
    }

    /**
     * Menentukan apakah pengguna dapat mengakses Panel Filament.
     * Ini adalah fungsi validasi yang dicari oleh Filament.
     */
    public function canAccessPanel(Panel $panel): bool {
        // Hanya izinkan akses jika peran pengguna adalah 'admin' 
        return $this->role === 'admin';
    }
}
