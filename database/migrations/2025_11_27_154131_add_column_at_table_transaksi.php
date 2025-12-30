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
            $table->foreignId('akun_debit_id')->constrained('akuns')->onDelete('cascade');
            $table->foreignId('akun_kredit_id')->constrained('akuns')->onDelete('cascade');
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->foreignId('akun_debit_id')->constrained('akuns')->onDelete('cascade');
            $table->foreignId('akun_kredit_id')->constrained('akuns')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            $table->dropForeign(['akun_debit_id']);
            $table->dropForeign(['akun_kredit_id']);
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->dropForeign(['akun_debit_id']);
            $table->dropForeign(['akun_kredit_id']);
        });
    }
};
