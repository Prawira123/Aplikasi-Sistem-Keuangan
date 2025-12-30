<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama', 
        'kategori',
        'harga',
        'stock',
        'harga_beli',
        'akun_persediaan',
    ];

    public function transaksiMasuks()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function transaksiKeluars()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }

    public function pakets()
    {
        return $this->hasMany(Paket::class);
    }
    
    public function laporan_transaksis(){
        return $this->hasMany(LaporanTransaksi::class);
    }

    public function akuns(){
        return $this->hasMany(Akun::class);
    } 
}
