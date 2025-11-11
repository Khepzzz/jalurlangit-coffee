<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $primaryKey = 'id_metode';
    protected $table = 'metode_pembayarans';

    public $timestamps = false;
    protected $fillable = [
        'nama_metode',
        'keterangan',
        'nomor_tujuan',
    ];

    // Relasi ke pembayaran
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_metode');
    }
}
