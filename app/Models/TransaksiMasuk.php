<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiMasuk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'karyawan_id',
        'product_id',
        'jasa_id',
        'kode',
        'tanggal',
        'keterangan',
        'tipe',
        'qty',
        'harga_satuan',
        'harga_total',
        'akun_debit_id',
        'akun_kredit_id',
        'jurnal_id',
        'pelanggan_id',
        'paket_id',
        'laporan_transaksi_id',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class);
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
        return $this->belongsTo(JurnalHeader::class, 'jurnal_id');
    }

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function paket(){
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function laporan_transaksi(){
        return $this->belongsTo(LaporanTransaksi::class, 'laporan_transaksi_id');
    }
}
