<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_masuks', 'jurnal_id')) {
                $table->unsignedBigInteger('jurnal_id')->nullable();
            }

            $table->foreign('jurnal_id')
                ->references('id')->on('jurnal_headers')
                ->onDelete('cascade');
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_keluars', 'jurnal_id')) {
                $table->unsignedBigInteger('jurnal_id')->nullable();
            }

            $table->foreign('jurnal_id')
                ->references('id')->on('jurnal_headers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            $table->dropForeign(['jurnal_id']);
            $table->dropColumn('jurnal_id');
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->dropForeign(['jurnal_id']);
            $table->dropColumn('jurnal_id');
        });
    }
};
