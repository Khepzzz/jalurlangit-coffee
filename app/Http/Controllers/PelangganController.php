<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pelanggan;
use Carbon\Carbon;

class PelangganController extends Controller
{
    // ✅ Tampilkan halaman ambil token + input identitas
    public function token()
    {
        // Cek dan perbarui status token yang sudah expired
        Pelanggan::where('expired_at', '<', Carbon::now('Asia/Jakarta'))
            ->where('status', '!=', 'expired')  // Pastikan hanya token yang belum expired
            ->update(['status' => 'expired']);

        // Ambil semua token dari database, urutkan berdasarkan status
        $tokens = Pelanggan::orderBy('status', 'desc')->get();
        return view('pelanggan.token', compact('tokens'));
    }

    // ✅ Generate Token Baru
    public function generateToken(Request $request)
    {
        // Cek apakah session token masih ada dan valid di database
        $sessionToken = session('token');
        if ($sessionToken) {
            $pelanggan = Pelanggan::where('token', $sessionToken)
                ->where('status', 'aktif')
                ->where('expired_at', '>', Carbon::now('Asia/Jakarta'))
                ->first();
    
            if ($pelanggan) {
                return response()->json([
                    'error' => 'Anda sudah pernah mengambil token dan token masih aktif!'
                ], 400);
            } else {
                // Jika session token tidak valid di DB (misalnya kadaluarsa), hapus session
                session()->forget('token');
            }
        }
    
        // Update status token yang sudah expired
        Pelanggan::where('expired_at', '<', Carbon::now('Asia/Jakarta'))
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);
    
        // Generate token baru
        $token = Str::random(10);
        $expired_at = Carbon::now('Asia/Jakarta')->addMinutes(120);  // 2 jam
    
        $pelanggan = Pelanggan::create([
            'token' => $token,
            'expired_at' => $expired_at,
            'status' => 'aktif'
        ]);
    
        // Simpan token baru ke session
        session(['token' => $pelanggan->token]);
    
        return response()->json([
            'token' => $pelanggan->token,
            'expired_at' => $pelanggan->expired_at->format('H:i'),
            'status' => $pelanggan->status
        ]);
    }
    

    // ✅ Simpan Identitas Pelanggan
    public function storeTokenData(Request $request)
    {
        // Validasi input
        $request->validate([
            'token' => 'required|exists:pelanggans,token', // Pastikan token ada di database
            'nama_pelanggan' => 'required|string|max:100',
            'nomor_kursi' => 'required|string|max:10',
        ]);

        // Cari token berdasarkan nilai yang diberikan
        $pelanggan = Pelanggan::where('token', $request->token)->first();

        // Cek apakah token tidak ditemukan atau sudah expired
        if (!$pelanggan) {
            return response()->json([
                'error' => 'Token tidak ditemukan atau sudah kadaluarsa!'
            ], 400);  // Status code 400 jika token tidak ditemukan
        }

        if ($pelanggan->expired_at < Carbon::now('Asia/Jakarta')) {
            $pelanggan->update(['status' => 'expired']);
            return response()->json([
                'error' => 'Token sudah kadaluarsa!'
            ], 400);  // Status code 400 jika token sudah kadaluarsa
        }

        // Simpan data pelanggan
        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_kursi' => $request->nomor_kursi,
        ]);
        session(['token' => $pelanggan->token]);
        return response()->json([
            'success' => true,
            'message' => 'Identitas berhasil disimpan!',
        ]);
    }

    // ✅ Cek Token Valid dan ambil data identitas jika sudah ada
    public function cekToken(Request $request)
    {
        $request->validate([
            'token' => 'required|exists:pelanggans,token',
        ]);
        
    
        $pelanggan = Pelanggan::where('token', $request->token)->first();
    
        // Cek apakah token valid dan belum expired
        if ($pelanggan->status !== 'aktif' || $pelanggan->expired_at < Carbon::now('Asia/Jakarta')) {
            return response()->json([
                'error' => 'Token tidak valid atau sudah kadaluarsa!',
            ], 400);
        }
    
        // Simpan token ke session agar bisa digunakan di halaman lain
        session(['token' => $pelanggan->token]);
    
        return response()->json([
            'success' => true,
            'message' => 'Token valid. Akses diperbolehkan.',
            'token' => $pelanggan->token,
            'nama_pelanggan' => $pelanggan->nama_pelanggan,
            'nomor_kursi' => $pelanggan->nomor_kursi,
            'data_tersimpan' => $pelanggan->nama_pelanggan && $pelanggan->nomor_kursi ? true : false,
        ]);
    }
    
    // 🆕 Ambil data pelanggan berdasarkan token
    public function getDataPelanggan(Request $request)
    {
        $token = $request->token;
        
        $pelanggan = Pelanggan::where('token', $token)
            ->where('status', 'aktif')
            ->where('expired_at', '>', Carbon::now('Asia/Jakarta'))
            ->first();
            
        if (!$pelanggan) {
            return response()->json([
                'error' => 'Token tidak valid atau sudah kadaluarsa!'
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'nama_pelanggan' => $pelanggan->nama_pelanggan,
            'nomor_kursi' => $pelanggan->nomor_kursi,
            'data_tersimpan' => !empty($pelanggan->nama_pelanggan) && !empty($pelanggan->nomor_kursi)
        ]);
    }
}