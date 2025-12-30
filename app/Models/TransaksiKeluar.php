<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiKeluar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'product_id',
        'akun_id',
        'kode',
        'tanggal',
        'keterangan',
        'qty',
        'harga_satuan',
        'harga_total',
        'akun_debit_id',
        'akun_kredit_id',
        'jurnal_id',
        'laporan_transaksi_id',
    ];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function akun(){
        return $this->belongsTo(Akun::class);
    }

    public function akun_debit()
    {
        return $this->belongsTo(Akun::class, 'akun_debit_id');
    }

    public function akun_kredit()
    {
        return $this->belongsTo(Akun::class, 'akun_kredit_id');
    }

    public function jurnal_header(){
        return $this->belongsTo(JurnalHeader::class);
    }

    public function laporan_transaksi(){
        return $this->belongsTo(LaporanTransaksi::class);
    }
}
