<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin
        User::create([
            'name' => 'Admin KukuruKuy',
            'email' => 'admin@kukurukuy.com',
            'password' => Hash::make('admin123'), // Gunakan Hash facade
            'email_verified_at' => now(), // Verifikasi email otomatis
            'role' => 'admin',
        ]);
    }
}
