<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Storage;

class ProductResource extends Resource {
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Manajemen Produk';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('price')->required()->numeric()->prefix('Rp'),

                        // === BAGIAN YANG DIPERBAIKI ===
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Gambar Produk')
                            ->disk('public')
                            ->image()
                            ->directory('product-images')
                            ->maxSize(2048) // Batasi ukuran upload maksimal menjadi 2MB (2048 KB)
                            ->imageResizeMode('cover') // Mode resize
                            ->imageResizeTargetWidth('1200') // Ubah lebar gambar menjadi 1200px
                            ->imageResizeTargetHeight('800'), // Ubah tinggi gambar menjadi 800px

                        Forms\Components\Textarea::make('description')->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Resep Produk')
                    ->description('Tentukan bahan baku dan jumlah yang dibutuhkan.')
                    ->schema([
                        Forms\Components\Repeater::make('ingredients')
                            ->label('Bahan Baku')
                            ->schema([
                                Forms\Components\Select::make('ingredient_id')
                                    ->label('Bahan Baku')
                                    ->options(Ingredient::all()->pluck('name', 'id'))
                                    ->searchable()->preload()->required()->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah Dibutuhkan')
                                    ->helperText('Satuan mengikuti satuan dasar bahan baku.')
                                    ->required()->numeric(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Tambah Bahan Baku')
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Produk')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('price')->money('IDR'),
                        Infolists\Components\ImageEntry::make('image_url')->label('Gambar'),
                        Infolists\Components\TextEntry::make('description')->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Resep Produk')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('ingredients')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')->label('Nama Bahan Baku')->weight('bold'),
                                Infolists\Components\TextEntry::make('pivot.quantity')->label('Jumlah Dibutuhkan'),
                                Infolists\Components\TextEntry::make('unit')->label('Satuan'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')->label('Gambar')->disk('public'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('price')->money('IDR')->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
