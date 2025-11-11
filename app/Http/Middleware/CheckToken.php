<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Carbon\Carbon;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/')->with('error', 'Silakan ambil token terlebih dahulu.');
        }

        $pelanggan = Pelanggan::where('token', $token)
            ->where('expired_at', '>', Carbon::now('Asia/Jakarta'))
            ->where('status', 'aktif')
            ->first();

        if (!$pelanggan) {
            session()->forget('token');
            return redirect('/')->with('error', 'Token tidak valid atau sudah kadaluarsa.');
        }

        return $next($request);
    }
}
