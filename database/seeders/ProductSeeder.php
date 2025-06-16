<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Ingredient;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Ambil data bahan baku
        $mie = Ingredient::where('name', 'Mie Basah')->first();
        $pangsit = Ingredient::where('name', 'Sambal Bawang')->first();
        $bakso = Ingredient::where('name', 'Bakso')->first();
        $saos = Ingredient::where('name', 'Saos')->first();
        $sawi = Ingredient::where('name', 'Sawi')->first();
        $sambalBawang = Ingredient::where('name', 'Sambal Bawang')->first();

        // Buat Produk 1: Paket Ayam Original
        $product1 = Product::create([
            'name' => 'Mie Ayam Original',
            'description' => 'Mie Ayam OG.',
            'price' => 12000,
            'image' => 'product-images/01JXRE8XQN8WJE4BC3DZPNSFW0.png' // Ganti dengan path yang sesuai
        ]);

        // Lampirkan bahan baku yang dibutuhkan untuk produk 1
        $product1->ingredients()->attach([
            $mie->id => ['quantity' => 1],
            $pangsit->id => ['quantity' => 30],   
            $saos->id => ['quantity' => 30],  
            $sawi->id => ['quantity' => 1],      
        ]);

        // Buat Produk 2: Paket Ayam Sambal Bawang
        $product2 = Product::create([
            'name' => 'Mie Ayam Bakso',
            'description' => 'Seporsi mie ayam yang disajikan dengan pentol hangat dan sambal bawang pedas.',
            'price' => 16000,
            'image' => 'product-images/01JXRFSY6YSJ4V4Y3MS9C4F0YA.png'
        ]);

        // Lampirkan bahan baku yang dibutuhkan untuk produk 2
        $product2->ingredients()->attach([
            $mie->id => ['quantity' => 1],
            $pangsit->id => ['quantity' => 30],
            $saos->id => ['quantity' => 30],
            $sawi->id => ['quantity' => 1],
            $sambalBawang ->id => ['quantity' => 10],
            $bakso ->id => ['quantity' => 4],
        ]);
    }
}
