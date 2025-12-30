<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelompok extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['kelompok'];

    public function kategori_akuns()
    {
        return $this->hasMany(KategoriAkun::class);
    }

    public function akuns()
    {
        return $this->hasMany(Akun::class);
    }
}
