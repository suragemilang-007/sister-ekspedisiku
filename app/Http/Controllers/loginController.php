<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
class loginController extends Controller
{
    public function showLogin()
    {
        if (Session::has('user_id')) {
            $role = Session::get('user_role');
            $name = Session::get('user_name');

            if ($role === 'admin') {
                return redirect()->intended('/dashboard/admin')
                    ->with('success', 'Selamat datang Admin ' . $name);
            } elseif ($role === 'pelanggan') {
                return redirect()->intended('/dashboard/pengirim')
                    ->with('success', 'Selamat datang ' . $name);
            }
        }
        return view('login_pengguna.login');
    }


    public function login(Request $request)
    {
        // Validasi form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.'
        ]);
        $pengguna = Pengguna::where('email', $request->email)->first();
        if ($pengguna && Hash::check($request->password, $pengguna->sandi_hash)) {
            Session::put('user_id', $pengguna->id_pengguna);
            Session::put('user_name', $pengguna->nama);
            Session::put('user_role', $pengguna->peran);

            // Redirect sesuai peran
            switch ($pengguna->peran) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard')
                        ->with('success', 'Selamat datang Admin ' . $pengguna->nama);
                case 'pelanggan':
                    return redirect()->intended('/dashboard/pengirim')
                        ->with('success', 'Selamat datang ' . $pengguna->nama);
                default:
                    return redirect('/')
                        ->with('warning', 'Peran pengguna tidak dikenali.');
            }
        }

        // Jika gagal login
        return back()->withInput()->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        Session::flush();
        return redirect('login')->with('success', 'Anda telah berhasil logout');
    }
}