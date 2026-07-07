<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'harga_per_satuan',
        'stok_saat_ini',
    ];

    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }
}
