<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'fullname', 
        'email', 
        'phone_number', 
        'address', 
        'birth_date', 
        'hire_date',
        'role_id',
        'status',
        'salary',
    ];

    public function transaksiMasuks()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function gaji_karyawans()
    {
        return $this->hasMany(GajiKaryawan::class);
    }

}
