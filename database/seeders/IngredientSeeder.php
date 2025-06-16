<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $ingredients = [
            ['name' => 'Ayam Cincang', 'unit' => 'gram'],
            ['name' => 'Sambal Bawang', 'unit' => 'gram'],
            ['name' => 'Mie Basah', 'unit' => 'pcs'],
            ['name' => 'Bakso', 'unit' => 'pcs'],
            ['name' => 'Saos', 'unit' => 'ml'],
            ['name' => 'Pangsit', 'unit' => 'gram'],
            ['name' => 'Air Mineral', 'unit' => 'botol'],
            ['name' => 'Sawi', 'unit' => 'pcs'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
