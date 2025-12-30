<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanTransaksi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'jasa_id',
        'paket_id',
        'supplier_id',
        'tanggal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
