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
        Schema::table('bk_logs', function (Blueprint $table) {
            // Tambahkan kolom baru setelah kolom 'poin'
            $table->text('tindak_lanjut')->nullable()->after('poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bk_logs', function (Blueprint $table) {
            $table->dropColumn('tindak_lanjut');
        });
    }
};
