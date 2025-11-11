<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans'; // Nama tabel di database

    protected $primaryKey = 'id_pelanggan';
    protected $fillable = 
    ['token', 
    'nama_pelanggan', 
    'nomor_kursi', 
    'expired_at', 
    'status'];
    public $timestamps = false; 

    // Menambahkan metode untuk mengecek apakah token sudah kadaluarsa
    public function isExpired()
    {
        return $this->expired_at < Carbon::now();
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_pelanggan');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }
}
