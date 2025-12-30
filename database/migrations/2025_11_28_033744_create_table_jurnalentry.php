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
        Schema::create('jurnal_headers', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('jurnal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_header_id')->constrained('jurnal_headers')->onDelete('cascade');
            $table->foreignId('akun_id')->constrained('akuns')->onDelete('cascade');
            $table->integer('nominal_debit');
            $table->integer('nominal_kredit');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_headers');
        Schema::dropIfExists('jurnal_details');
    }
};
