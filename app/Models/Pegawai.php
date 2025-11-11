<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use Notifiable;

    protected $table = 'pegawais'; // pakai tabel pegawais

    protected $primaryKey = 'id_pegawai'; // primary key sesuai tabel

    protected $fillable = [
        'nama_pegawai', 
        'email', 
        'password'
    ];

    public $timestamps = false; // tambahkan ini untuk menonaktifkan timestamps

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Helper untuk cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
