<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Primary key untuk model
     */
    protected $primaryKey = 'id_pembayaran';
    
    /**
     * Kolom yang bisa diisi
     */
    protected $fillable = [
        'id_pesanan',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'jumlah',
        'status_pembayaran',
        'bukti_pembayaran'
    ];
    
    /**
     * Kolom yang harus dikonversi ke tipe data tertentu
     */
    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];
    
    /**
     * Sesuai dengan database tanpa timestamps
     */
    public $timestamps = false;
    
    /**
     * Relasi ke pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
    
    /**
     * Accessor untuk URL bukti pembayaran
     */
    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran) {
            return asset('storage/' . $this->bukti_pembayaran);
        }
        return null;
    }
    
    /**
     * Accessor untuk status pembayaran yang lebih user-friendly
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '<span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">Menunggu</span>',
            'proses' => '<span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">Diproses</span>',
            'dibayar' => '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Dibayar</span>',
        ];
        
        return $labels[$this->status_pembayaran] ?? '';
    }
}