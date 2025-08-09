<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fungsi ini akan dijalankan saat migrasi
        Schema::table('bk_logs', function (Blueprint $table) {
            // Mengubah kolom 'poin' menjadi integer yang bisa null (nullable)
            $table->integer('poin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fungsi ini untuk membatalkan migrasi (rollback)
        Schema::table('bk_logs', function (Blueprint $table) {
            // Mengembalikan kolom 'poin' menjadi tidak bisa null
            $table->integer('poin')->nullable(false)->change();
        });
    }
};
