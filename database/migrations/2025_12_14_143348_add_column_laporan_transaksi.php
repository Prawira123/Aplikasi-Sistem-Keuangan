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
        Schema::table('transaksi_masuks', function (Blueprint $table){
            $table->foreignId('laporan_transaksi_id')->nullable()->constrained('laporan_transaksis')->onDelete('cascade');
        });
        Schema::table('transaksi_keluars', function (Blueprint $table){
            $table->foreignId('laporan_transaksi_id')->nullable()->constrained('laporan_transaksis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuks', function (Blueprint $table){
            $table->dropForeign(['laporan_transaksi_id']);
            $table->dropColumn('laporan_transaksi_id');
        });

        Schema::table('transaksi_keluars', function (Blueprint $table){
            $table->dropForeign(['laporan_transaksi_id']);
            $table->dropColumn('laporan_transaksi_id');
        });
    }
};
