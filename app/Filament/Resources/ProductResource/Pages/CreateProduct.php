<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord {
    protected static string $resource = ProductResource::class;

    /**
     * Override method ini untuk menangani data resep (ingredients) secara manual.
     */
    protected function handleRecordCreation(array $data): Model {
        // 1. Simpan data produk utama (name, price, dll)
        $product = static::getModel()::create($data);

        // 2. Jika ada data resep di dalam form
        if (isset($data['ingredients'])) {
            $ingredientsData = [];
            foreach ($data['ingredients'] as $ingredient) {
                // Format: [ingredient_id => ['quantity' => nilai]]
                $ingredientsData[$ingredient['ingredient_id']] = ['quantity' => $ingredient['quantity']];
            }
            // 3. Gunakan method sync() untuk menyimpan relasi many-to-many
            $product->ingredients()->sync($ingredientsData);
        }

        return $product;
    }
}
