<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalHeader extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tanggal',
        'keterangan'
    ];

    public function jurnal_details(){
        return $this->hasMany(JurnalDetail::class);
    }

    public function transaksi_masuks(){
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function transaksi_keluars(){
        return $this->hasMany(TransaksiKeluar::class);
    }

    public function akuns(){
        return $this->hasMany(Akun::class);
    }
}
