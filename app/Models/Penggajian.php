<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajian';

    protected $fillable = [
        'user_id',
        'periode_mulai',
        'periode_akhir',
        'total_hadir',
        'total_alpa',
        'total_gaji_pokok',
        'total_potongan',
        'gaji_bersih_akhir',
        'status_pembayaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
