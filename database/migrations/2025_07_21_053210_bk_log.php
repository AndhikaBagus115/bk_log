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

        Schema::create('bk_logs', function (Blueprint $table) {
        $table->id();
        $table->integer('nomor_absen');
        $table->string('nama_murid');
        $table->string('kelas');
        $table->text('catatan');
        $table->integer('poin');
        $table->date('tanggal_input')->default(now());
        $table->integer('minggu_ke')->nullable();
        $table->string('bulan')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_logs');
    }
};
