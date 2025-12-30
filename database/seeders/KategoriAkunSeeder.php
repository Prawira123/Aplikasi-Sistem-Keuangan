<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriAkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            // ================= NERACA =================
            [
                'kode' => '101',
                'nama' => 'ASET LANCAR',
                'kelompok_id' => 1,
                'created_at' => now(),
            ],
            [
                'kode' => '102',
                'nama' => 'ASET TETAP',
                'kelompok_id' => 1,
                'created_at' => now(),
            ],
            [
                'kode' => '201',
                'nama' => 'LIABILITAS JANGKA PENDEK',
                'kelompok_id' => 2,
                'created_at' => now(),
            ],
            [
                'kode' => '202',
                'nama' => 'LIABILITAS JANGKA PANJANG',
                'kelompok_id' => 2,
                'created_at' => now(),
            ],
            [
                'kode' => '301',
                'nama' => 'MODAL',
                'kelompok_id' => 3,
                'created_at' => now(),
            ],

            // ================= LABA RUGI =================
            [
                'kode' => '401',
                'nama' => 'PENDAPATAN USAHA',
                'kelompok_id' => 4,
                'created_at' => now(),
                
            ],
            [
                'kode' => '501',
                'nama' => 'HPP',
                'kelompok_id' => 5,
                'created_at' => now(),
                
            ],
            [
                'kode' => '502',
                'nama' => 'BEBAN OPERASIONAL',
                'kelompok_id' => 5,
                'created_at' => now(),
                
            ],
            [
                'kode' => '503',
                'nama' => 'BEBAN NON OPERASIONAL',
                'kelompok_id' => 5,  
                'created_at' => now(),
            ],
            [
                'kode' => '103',
                'nama' => 'PERSEDIAAN',
                'kelompok_id' => 1,  
                'created_at' => now(),
            ],
            
        ];

        DB::table('kategori_akuns')->insert($categories);
    }
}

