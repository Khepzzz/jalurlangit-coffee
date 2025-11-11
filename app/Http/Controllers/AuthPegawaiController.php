<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;

class AuthPegawaiController extends Controller
{
    // Menampilkan form login pegawai
    public function showLoginForm()
    {
        return view('pegawai.login');
    }

    // Menampilkan form register pegawai
    public function showRegisterForm()
    {
        return view('pegawai.register');
    }

    // Proses login pegawai
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('pegawai')->attempt($credentials)) {
            return redirect()->intended('/pegawai/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->withInput($request->only('email'));
    }

    // Proses register pegawai
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawais,email',
            'password' => 'required|string|min:6|confirmed', // password confirmation harus tersedia di form
        ]);

        // Simpan pegawai baru
        Pegawai::create([
            'nama_pegawai' => $request->name, // disesuaikan
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect ke login atau auto login
        return redirect()->route('pegawai.login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Logout pegawai
    public function logout()
    {
        Auth::guard('pegawai')->logout();
        return redirect('/pegawai/login');
    }
}
