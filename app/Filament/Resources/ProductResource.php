<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Ingredient; // <-- PENTING: Tambahkan ini
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource {
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?string $modelLabel = 'Produk Menu';
    protected static ?string $pluralModelLabel = 'Produk Menu';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Jual')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Gambar Produk')
                            ->image()
                            ->directory('product-images'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Resep Produk')
                    ->description('Tentukan bahan baku dan jumlah yang dibutuhkan untuk membuat satu porsi produk ini.')
                    ->schema([
                        Forms\Components\Repeater::make('ingredients')
                            ->label('Bahan Baku')
                            ->relationship()
                            ->schema([
                                // === BAGIAN YANG DIPERBAIKI ===
                                Forms\Components\Select::make('ingredient_id')
                                    ->label('Bahan Baku')
                                    // Hapus ->relationship() dan ganti dengan ->options()
                                    ->options(Ingredient::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct() // Mencegah bahan baku yang sama dipilih dua kali
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(), // Sembunyikan jika sudah dipilih
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric(),
                                // Anda bisa menghapus kolom unit dari sini jika sudah ditentukan di Ingredient
                                // atau biarkan jika resepnya bisa menggunakan satuan berbeda
                                Forms\Components\Select::make('unit')
                                    ->label('Satuan')
                                    ->options([
                                        'gram' => 'Gram',
                                        'ml' => 'Mililiter',
                                        'pcs' => 'Pcs',
                                    ])
                                    ->required(),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Bahan Baku'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table {
        // ... (Method table tidak perlu diubah, biarkan seperti sebelumnya) ...
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Gambar'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ... (getRelations dan getPages biarkan seperti sebelumnya) ...
    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
