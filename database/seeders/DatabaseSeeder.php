<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\Jasa;
use App\Models\User;
use App\Models\Paket;
use App\Models\Product;
use App\Models\Karyawan;
use App\Models\Kelompok;
use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use App\Models\KategoriAkun;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Erick Prawira',
            'email' => 'prawirawinata123@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner'
        ]);

        Karyawan::factory(20)->create();

        Supplier::create([
            'nama' => 'Supplier 1',
            'no_tlp' => '08123456789',
            'alamat' => 'Jl. Contoh, Kota Contoh'
        ]);

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

        // KategoriAkun::create([
        //     'nama' => 'Aset',
        //     'kelompok_id' => 1,
        //     'kode' => '110',
        //     'created_at' => now(),
        // ]);

        // Akun::insert([
        //     [
        //         'kategori_akun_id' => 1,
        //         'kelompok_id' => 1,
        //         'normal_post' => 'Debit',
        //         'kode' => '1101',
        //         'nama' => 'Aset Tetap',
        //         'created_at' => now(),
        //     ],
        //     [
        //         'kategori_akun_id' => 1,
        //         'kelompok_id' => 1,
        //         'normal_post' => 'Debit',
        //         'kode' => '1102',
        //         'nama' => 'Aset Lancar',
        //         'created_at' => now(),
        //     ]

        // ]);

        // Pelanggan::create([
        //     'nama' => 'Pelanggan 1',
        //     'no_tlp' => '0812345',
        //     'alamat' => 'Jl. Contoh, Kota Contoh',
        //     'nik' => '123456789',
        //     'jenis_kelamin' => 'Laki-laki'
        // ]);

        // Product::create([
        //     'nama' => 'Product 1',
        //     'kategori' => 'Kategori 1',
        //     'harga' => 10000,
        //     'stock' => null
        // ]);

        // Jasa::create([
        //     'nama' => 'Jasa 1',
        //     'harga' => 10000
        // ]);

        // Paket::create([
        //     'nama' => 'Paket 1',
        //     'harga' => 10000,
        //     'product_id' => 1,
        //     'jasa_id' => 1,
        // ]);

        // JurnalHeader::create([
        //     'tanggal' => now(),
        //     'keterangan' => 'test'
        // ]);

        // JurnalDetail::insert([
        //     [
        //         'jurnal_header_id' => 1,
        //         'akun_id' => 1,
        //         'nominal_debit' => 10000,
        //         'nominal_kredit' => 0
        //     ], 
        //     [
        //         'jurnal_header_id' => 1,
        //         'akun_id' => 2,
        //         'nominal_debit' => 0,
        //         'nominal_kredit' => 10000
        //     ]
        // ]);

        // TransaksiMasuk::create([
        //     'product_id' => 1,
        //     'karyawan_id' => 1,
        //     'pelanggan_id' => 1,
        //     'jasa_id' => 1,
        //     'paket_id' => 1,
        //     'kode' => 'TM001',
        //     'tanggal' => now(),
        //     'keterangan' => 'test',
        //     'tipe' => 'barang',
        //     'qty' => 1,
        //     'harga_satuan' => 10000,
        //     'harga_total' => 10000,
        //     'akun_debit_id' => 1,
        //     'akun_kredit_id' => 2,
        //     'created_at' => now(),
        //     'jurnal_id' => 1
        // ]);
        
        // TransaksiKeluar::create([
        //     'product_id' => 1,
        //     'supplier_id' => 1,
        //     'kode' => 'TK001',
        //     'tanggal' => now(),
        //     'keterangan' => 'test',
        //     'qty' => 10,
        //     'harga_satuan' => 100000,
        //     'harga_total' => 10000,
        //     'akun_debit_id' => 1,
        //     'akun_kredit_id' => 1,
        //     'created_at' => now(),
        //     'jurnal_id' => 1
        // ]);

        
    }
}
