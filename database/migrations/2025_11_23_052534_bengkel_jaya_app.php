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
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_tlp')->unique();
            $table->text('alamat');
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('kategori_akuns', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode');
            $table->foreignId('kelompok_id')->constrained('kelompoks')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->integer('harga');
            $table->integer('stock')->nullable();
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->string('normal_post')->nullable();
            $table->foreignId('kelompok_id')->nullable()->constrained('kelompoks')->onDelete('set null');
            $table->foreignId('kategori_akun_id')->nullable()->constrained('kategori_akuns')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('jasas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('harga');
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('fullname'); 
            $table->string('email')->unique(); 
            $table->string('phone_number'); 
            $table->text('address'); 
            $table->date('birth_date'); 
            $table->date('hire_date'); 
            $table->decimal('salary', 10, 2); 
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('transaksi_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('jasa_id')->nullable()->constrained('jasas')->onDelete('cascade');
            $table->string('kode');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->enum('tipe', ['jasa', 'barang', 'paket']);
            $table->integer('qty')->nullable();
            $table->integer('harga_satuan')->nullable();
            $table->integer('harga_total');
            $table->timestamps();
            $table->softDeletes();  
        });

        Schema::create('transaksi_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('kode');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->integer('qty');
            $table->integer('harga_satuan');
            $table->integer('harga_total');
            $table->timestamps();
            $table->softDeletes();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
        Schema::dropIfExists('kategori_akuns');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('akuns');
        Schema::dropIfExists('jasas');
        Schema::dropIfExists('karyawans');
        Schema::dropIfExists('transaksi_masuks');
        Schema::dropIfExists('transaksi_keluars');
    }
};
