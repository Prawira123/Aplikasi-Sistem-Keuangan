<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'jurnal_header_id',
        'akun_id',
        'nominal_debit',
        'nominal_kredit',
    ];

    public function jurnal_header(){
        return $this->belongsTo(JurnalHeader::class);
    }

    public function akun(){
        return $this->belongsTo(Akun::class, 'akun_id');
    }

}
