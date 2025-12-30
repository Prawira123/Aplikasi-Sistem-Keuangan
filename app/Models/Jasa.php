<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jasa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama', 
        'harga',
    ];

    public function transaksiMasuks()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function pakets()
    {
        return $this->hasMany(Paket::class);
    }

    public function laporan_transaksis(){
        return $this->hasMany(LaporanTransaksi::class);
    }
}
