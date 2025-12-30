<?php

namespace App\Models;

use App\Models\Akun;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriAkun extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama', 
        'kelompok_id',
        'kode'
    ];

    public function akuns(){
        return $this->hasMany(Akun::class, 'kategori_akun_id');
    }

    public function kelompok(){
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

}
