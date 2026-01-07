<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GajiKaryawan extends Model
{
    protected $fillable = [
        'karyawan_id', 'total_gaji', 'kehadiran', 'status'
    ];

    public function karyawan(){
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
