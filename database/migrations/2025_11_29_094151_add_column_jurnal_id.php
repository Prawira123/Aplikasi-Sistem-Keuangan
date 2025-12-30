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
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            $table->unsignedBigInteger('jurnal_id')->nullable();
        });
        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->unsignedBigInteger('jurnal_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            $table->dropColumn('jurnal_id');
        });
        
        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->dropColumn('jurnal_id');
        });

    }
};
