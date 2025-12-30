<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('akuns')->insert([
    // KAS
            [
                'kode'=>'1011','nama'=>'KAS','normal_post'=>'Debit',
                'kategori_akun_id'=>1,'kelompok_id'=>1,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],
            [
                'kode'=>'1012','nama'=>'BANK BCA','normal_post'=>'Debit',
                'kategori_akun_id'=>1,'kelompok_id'=>1,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],

            // PERSEDIAAN
            [
                'kode'=>'1013','nama'=>'PERSEDIAAN OLI','normal_post'=>'Debit',
                'kategori_akun_id'=>10,'kelompok_id'=>1,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],
            [
                'kode'=>'1014','nama'=>'PERSEDIAAN BAN','normal_post'=>'Debit',
                'kategori_akun_id'=>10,'kelompok_id'=>1,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],

            // ASET TETAP
            [
                'kode'=>'1021','nama'=>'PERALATAN','normal_post'=>'Debit',
                'kategori_akun_id'=>2,'kelompok_id'=>1,'aktivitas_kas'=>'Investasi','created_at' => now(),
            ],

            // KEWAJIBAN
            [
                'kode'=>'2011','nama'=>'UTANG USAHA','normal_post'=>'Kredit',
                'kategori_akun_id'=>3,'kelompok_id'=>2,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],

            // EKUITAS
            [
                'kode'=>'3011','nama'=>'MODAL','normal_post'=>'Kredit',
                'kategori_akun_id'=>4,'kelompok_id'=>3,'aktivitas_kas'=>'Pendanaan','created_at' => now(),
            ],
            [
                'kode'=>'3012','nama'=>'PRIVE','normal_post'=>'Debit',
                'kategori_akun_id'=>4,'kelompok_id'=>3,'aktivitas_kas'=>'Pendanaan','created_at' => now(),
            ],

            // PENDAPATAN
            [
                'kode'=>'4011','nama'=>'PENJUALAN BARANG','normal_post'=>'Kredit',
                'kategori_akun_id'=>6,'kelompok_id'=>4,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],

            // BEBAN
            [
                'kode'=>'5011','nama'=>'HPP','normal_post'=>'Debit',
                'kategori_akun_id'=>7,'kelompok_id'=>5,'aktivitas_kas'=>'Operasional','created_at' => now(),
            ],
        ]);

    }
}
