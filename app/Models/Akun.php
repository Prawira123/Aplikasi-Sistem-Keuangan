<?php

namespace App\Models;

use App\Models\TransaksiKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Akun extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode', 
        'nama', 
        'kelompok_id', 
        'normal_post', 
        'kategori_akun_id',
        'saldo_awal',
        'lawan_post',
        'jurnal_id',
        'aktivitas_kas',
        'saldo_sementara',
    ];

    public function kategori_akun()
    {
        return $this->belongsTo(KategoriAkun::class, 'kategori_akun_id');
    }

    public function transaksiKeluars()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function jurnal_header(){
        return $this->belongsTo(JurnalHeader::class);
    }

    public function product(){
        $this->belongsTo(Product::class, 'akun_persediaan');
    }
}
