<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Perintah ini untuk MEMODIFIKASI tabel 'users' yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' setelah kolom 'password'
            $table->string('role')->default('cashier')->after('password');

            // Tambahkan kolom 'franchise_id' dan foreign key-nya
            $table->foreignId('franchise_id')
                ->nullable()
                ->after('role') // Letakkan setelah kolom role
                ->constrained('franchises') // Nama tabel referensi
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        // Perintah ini untuk membatalkan (rollback) apa yang kita lakukan di up()
        Schema::table('users', function (Blueprint $table) {
            // Penting: Hapus foreign key dulu sebelum menghapus kolomnya
            $table->dropForeign(['franchise_id']);
            $table->dropColumn(['franchise_id', 'role']);
        });
    }
};
