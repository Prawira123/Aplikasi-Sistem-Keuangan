<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama', 'alamat', 'no_tlp', 'nik', 'jenis_kelamin',
    ];

    public function transaksi(){
        return $this->hasMany(TransaksiMasuk::class);
    }
}
