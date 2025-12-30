<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama', 
        'no_tlp',
        'alamat',
    ];

    public function transaksiKeluars()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }

    public function laporan_transaksis(){
        return $this->hasMany(LaporanTransaksi::class);
    }

}
