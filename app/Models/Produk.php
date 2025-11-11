<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'kategori',
        'deskripsi',
        'harga',
        'stok',
        'gambar_produk'
    ];

    public $timestamps = false; 

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_produk');
    }
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_produk');
    }

}
