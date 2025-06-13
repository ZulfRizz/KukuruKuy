<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists; // Import Infolist
use Filament\Infolists\Infolist;

class OrderResource extends Resource {
    protected static ?string $model = Order::class;

    // Konfigurasi untuk menu navigasi
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $modelLabel = 'Laporan Penjualan';
    protected static ?string $pluralModelLabel = 'Laporan Penjualan';

    // Kita tidak menyediakan form karena order dibuat dari antarmuka kasir
    public static function form(Form $form): Form {
        return $form->schema([]);
    }

    // Infolist untuk menampilkan detail saat aksi 'View' diklik
    public static function infolist(Infolist $infolist): Infolist {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('invoice_number')->label('No. Invoice'),
                        Infolists\Components\TextEntry::make('franchise.name')->label('Cabang'),
                        Infolists\Components\TextEntry::make('user.name')->label('Kasir'),
                        Infolists\Components\TextEntry::make('total_amount')->label('Total Pembayaran')->money('IDR'),
                        Infolists\Components\TextEntry::make('created_at')->label('Waktu Transaksi')->dateTime(),
                    ])->columns(2),
                Infolists\Components\Section::make('Detail Produk Dipesan')
                    ->schema([
                        // Menampilkan daftar produk yang ada di dalam pesanan ini
                        Infolists\Components\RepeatableEntry::make('details')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')->label('Nama Produk')->weight('bold'),
                                Infolists\Components\TextEntry::make('quantity')->label('Jumlah'),
                                Infolists\Components\TextEntry::make('price')->label('Harga Satuan')->money('IDR'),
                                Infolists\Components\TextEntry::make('subtotal')->label('Subtotal')->money('IDR'),
                            ])->columns(4)
                    ])
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('franchise.name')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kasir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pesanan')
                    ->dateTime('d-M-Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Urutkan berdasarkan pesanan terbaru
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tidak ada EditAction
            ])
            ->bulkActions([]); // Tidak ada bulk actions
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    // Hanya izinkan halaman 'index' dan 'view'
    public static function getPages(): array {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    // Mencegah user membuat atau mengedit order dari panel admin
    public static function canCreate(): bool {
        return false;
    }
}
