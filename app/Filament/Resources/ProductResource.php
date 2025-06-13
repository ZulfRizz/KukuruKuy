<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource {
    protected static ?string $model = Product::class;

    // Konfigurasi untuk menu navigasi
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
                            ->directory('product-images'), // Folder penyimpanan gambar
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Resep Produk')
                    ->description('Tentukan bahan baku dan jumlah yang dibutuhkan untuk membuat satu porsi produk ini.')
                    ->schema([
                        // Fitur canggih untuk manajemen resep (relasi many-to-many)
                        Forms\Components\Repeater::make('ingredients')
                            ->label('Bahan Baku')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('ingredient_id')
                                    ->label('Bahan Baku')
                                    ->relationship('ingredient', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric(),
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
