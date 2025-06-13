<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FranchiseResource\Pages;
use App\Models\Franchise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FranchiseResource extends Resource {
    protected static ?string $model = Franchise::class;

    // Konfigurasi untuk menu navigasi di panel admin
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Manajemen Bisnis';
    protected static ?string $modelLabel = 'Cabang (Franchise)';
    protected static ?string $pluralModelLabel = 'Cabang (Franchise)';


    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Cabang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->tel() // Memberi validasi input telepon
                    ->required()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Cabang')
                    ->searchable(), // Membuat kolom ini bisa dicari
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(50) // Batasi teks agar tidak terlalu panjang
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Bisa disembunyikan
            ])
            ->filters([
                // Filter bisa ditambahkan di sini jika perlu
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
            // Relasi bisa ditambahkan di sini, misal untuk melihat user di cabang tsb
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListFranchises::route('/'),
            'create' => Pages\CreateFranchise::route('/create'),
            'edit' => Pages\EditFranchise::route('/{record}/edit'),
        ];
    }
}
