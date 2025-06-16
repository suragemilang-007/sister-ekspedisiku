<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
class loginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if ($pengguna && Hash::check($request->password, $pengguna->sandi_hash)) {
            Session::put('user_id', $pengguna->id_pengguna);
            Session::put('user_name', $pengguna->nama);
            Session::put('user_role', $pengguna->peran);

            // Redirect berdasarkan peran
            if ($pengguna->peran === 'admin') {
                return redirect()->intended('/dashboard/admin')->with('success', 'Selamat datang Admin ' . $pengguna->nama);
            } elseif ($pengguna->peran === 'pelanggan') {
                return redirect()->intended('/dashboard/pengirim')->with('success', 'Selamat datang ' . $pengguna->nama);
            }

            return redirect('/')->with('warning', 'Peran pengguna tidak dikenali.');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang anda masukkan salah.',
        ]);
    }

    public function logout()
    {
        Session::flush();
        return redirect('login')->with('success', 'Anda telah berhasil logout');
    }
}