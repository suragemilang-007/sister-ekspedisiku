<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class pengaturanAkunController extends Controller
{
    public function edit()
    {
        $userId = Session::get('user_uid');
        $pengguna = \DB::table('pengguna')->where('uid', $userId)->first();
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function updateInfo(Request $request)
    {
        $data = $request->only(['nama', 'email', 'tgl_lahir', 'nohp', 'alamat', 'kelamin']);
        $data['uid'] = $request->uid; // Use uid from request instead of session

        Http::post('http://localhost:3001/pengguna/update-info', $data);
        return response()->json(['status' => 'ok']);
    }
 
    public function updatePassword(Request $request)
    {
        $data = [
            'uid' => $request->uid,
            'password' => $request->password
        ];

        Http::post('http://localhost:3001/pengguna/update-password', $data);
        return response()->json(['status' => 'ok']);
    }
}