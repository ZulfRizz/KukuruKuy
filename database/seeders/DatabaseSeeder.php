<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,      // Membuat user Admin
            FranchiseSeeder::class,      // Membuat data franchise
            UserRoleSeeder::class,       // Membuat manajer & kasir untuk franchise
            IngredientSeeder::class,     // Membuat data bahan baku
            ProductSeeder::class,        // Membuat produk & relasinya ke bahan baku
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
