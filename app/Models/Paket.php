<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $fillable = [
        'nama', 'product_id', 'jasa_id', 'harga',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class);
    }

    public function laporan_transaksis(){
        return $this->hasMany(LaporanTransaksi::class);
    }
}
