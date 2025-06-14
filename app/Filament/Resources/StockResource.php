<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter; // <-- Import untuk filter

class StockResource extends Resource {
    protected static ?string $model = Stock::class;

    // Pindahkan ke grup Manajemen Produk agar logis
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    // protected static ?string $modelLabel = 'Stok Bahan Baku';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('franchise_id')
                    ->relationship('franchise', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('ingredient_id')
                    ->relationship('ingredient', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('franchise.name')
                    // Hapus ->numeric(), karena nama adalah teks
                    ->searchable() // Tambahkan searchable
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingredient.name')
                    // Hapus ->numeric()
                    ->searchable() // Tambahkan searchable
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                // Tambahkan kolom satuan agar lebih jelas
                Tables\Columns\TextColumn::make('ingredient.unit')
                    ->label('Satuan'),
            ])
            ->filters([
                // Tambahkan filter berdasarkan cabang
                SelectFilter::make('franchise')
                    ->relationship('franchise', 'name')
                    ->label('Filter Cabang')
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

    public static function getPages(): array {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
