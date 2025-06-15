<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action; // Import Action class

class UserResource extends Resource {
    protected static ?string $model = User::class;

    // Konfigurasi untuk menu navigasi di panel admin
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    // protected static ?string $modelLabel = 'Pengguna';
    // protected static ?string $pluralModelLabel = 'Pengguna';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // Memastikan email unik, mengabaikan record saat ini (untuk edit)

                // Logika password yang benar:
                // 1. Enkripsi otomatis saat disimpan.
                // 2. Hanya wajib diisi saat membuat user baru, opsional saat edit.
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),

                // Dropdown untuk memilih peran (role)
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'manager' => 'Manajer Cabang',
                        'cashier' => 'Kasir',
                    ])
                    ->required(),

                // Dropdown untuk memilih cabang, terhubung ke tabel Franchise
                Forms\Components\Select::make('franchise_id')
                    ->label('Ditempatkan di Cabang')
                    ->relationship('franchise', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                // Menampilkan status verifikasi email dengan ikon (lebih baik)
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->boolean()
                    ->sortable(),

                // Menampilkan role dengan badge berwarna
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'manager' => 'warning',
                        'cashier' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('franchise.name')
                    ->label('Cabang')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Pusat / Tidak ada cabang'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter bisa ditambahkan di sini
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // Aksi kustom untuk verifikasi manual
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-s-check-badge')
                    ->color('success')
                    ->action(fn(User $record) => $record->forceFill(['email_verified_at' => now()])->save())
                    ->requiresConfirmation() // Minta konfirmasi sebelum menjalankan aksi
                    ->visible(fn(User $record): bool => !$record->hasVerifiedEmail()), // Hanya tampil jika user belum terverifikasi
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
