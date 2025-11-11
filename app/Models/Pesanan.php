<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'total_harga',
        'tanggal_pesanan',
        'status_pesanan'
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_pesanan');
    }

        // Relasi dengan Pembayaran
        public function pembayaran()
        {
            return $this->hasOne(Pembayaran::class, 'id_pesanan');
        }
}
