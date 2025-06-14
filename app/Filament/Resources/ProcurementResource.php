<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcurementResource\Pages;
use App\Models\Procurement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ProcurementResource extends Resource {
    protected static ?string $model = Procurement::class;

    // Konfigurasi untuk Navigasi
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2; // Urutan di dalam grup menu

    // Admin tidak membuat permintaan, hanya mengelola.
    // Form ini hanya untuk mengedit status.
    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan dari Cabang (Read-only)')
                    ->disabled(),
            ]);
    }

    // Untuk menampilkan detail saat tombol "View" diklik
    public static function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            Infolists\Components\TextEntry::make('franchise.name'),
            Infolists\Components\TextEntry::make('user.name')->label('Diajukan Oleh'),
            Infolists\Components\TextEntry::make('status')->badge()->color(fn(string $state) => match ($state) {
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'danger',
                'completed' => 'gray',
            }),
            Infolists\Components\RepeatableEntry::make('details')->schema([
                Infolists\Components\TextEntry::make('ingredient.name'),
                Infolists\Components\TextEntry::make('quantity'),
                Infolists\Components\TextEntry::make('ingredient.unit')->label('Satuan'),
            ])->columns(3),
        ]);
    }

    // Untuk menampilkan tabel utama
    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->date()->sortable(),
                Tables\Columns\TextColumn::make('franchise.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pemohon'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state) => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    'completed' => 'gray',
                }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'completed' => 'Completed',
                ]),
                Tables\Filters\SelectFilter::make('franchise')->relationship('franchise', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListProcurements::route('/'),
            'view' => Pages\ViewProcurement::route('/{record}'),
            'edit' => Pages\EditProcurement::route('/{record}/edit'),
        ];
    }

    // Mencegah Admin membuat permintaan baru dari panel ini
    public static function canCreate(): bool {
        return false;
    }
}
