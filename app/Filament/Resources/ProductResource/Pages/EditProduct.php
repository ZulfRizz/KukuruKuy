<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProduct extends EditRecord {
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Override method ini untuk mengisi data resep ke dalam form saat halaman edit dibuka.
     */
    protected function mutateFormDataBeforeFill(array $data): array {
        $data['ingredients'] = $this->getRecord()->ingredients->map(function ($ingredient) {
            return [
                'ingredient_id' => $ingredient->id,
                'quantity' => $ingredient->pivot->quantity,
            ];
        })->toArray();

        return $data;
    }

    /**
     * Override method ini untuk menangani penyimpanan data resep saat produk di-update.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model {
        // Update data produk utama
        $record->update($data);

        // Siapkan dan sinkronkan data resep yang baru
        $ingredientsData = [];
        if (isset($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingredient) {
                $ingredientsData[$ingredient['ingredient_id']] = ['quantity' => $ingredient['quantity']];
            }
        }
        $record->ingredients()->sync($ingredientsData);

        return $record;
    }
}
