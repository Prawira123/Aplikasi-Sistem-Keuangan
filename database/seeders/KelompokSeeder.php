<?php

namespace Database\Seeders;

use App\Models\Kelompok;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kelompok::insert([
            [
                'name' => 'Aset'
            ],
            [
                'name' => 'Kewajiban'
            ],
            [
                'name' => 'Ekuitas'
            ],
            [
                'name' => 'Pendapatan'
            ],
            [
                'name' => 'Beban'
            ]
        ]);
    }
}
