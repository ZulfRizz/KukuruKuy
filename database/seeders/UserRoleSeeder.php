<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Franchise;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $franchisePusat = Franchise::where('name', 'KukuruKuy Bojonegoro')->first();
        $franchiseSurabaya = Franchise::where('name', 'KukuruKuy Surabaya')->first();

        // === Buat Manajer ===
        if ($franchisePusat) {
            User::create([
                'name' => 'Manajer Bojonegoro',
                'email' => 'manajer.bjn@kukurukuy.com',
                'password' => Hash::make('manajer123'),
                'role' => 'manajer',
                'franchise_id' => $franchisePusat->id,
            ]);
        }

        if ($franchiseSurabaya) {
            User::create([
                'name' => 'Manajer Surabaya',
                'email' => 'manajer.surabaya@kukurukuy.com',
                'password' => Hash::make('manajer123'),
                'role' => 'manajer',
                'franchise_id' => $franchiseSurabaya->id,
            ]);
        }

        // === Buat Kasir ===
        if ($franchisePusat) {
            User::create([
                'name' => 'Kasir 1 Bojonegoro',
                'email' => 'kasir1.bjn@kukurukuy.com',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'franchise_id' => $franchisePusat->id,
            ]);
        }

        if ($franchiseSurabaya) {
            User::create([
                'name' => 'Kasir 1 Surabaya',
                'email' => 'kasir1.surabaya@kukurukuy.com',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'franchise_id' => $franchiseSurabaya->id,
            ]);
        }
    }
}
