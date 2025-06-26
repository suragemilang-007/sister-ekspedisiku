<?php

namespace App\Http\Controllers;

use App\Models\AlamatPenjemputan;
use App\Models\Pengguna;
use App\Models\ZonaPengiriman;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Str;

class AlamatPenjemputanController extends Controller
{
    /**
     * Display a listing of the resource for logged in user
     */
    public function index()
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $alamatPenjemputan = AlamatPenjemputan::where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengguna.alamat-penjemputan.index', compact('alamatPenjemputan'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $userId = Session::get('user_uid');
        $kecamatanAsal = ZonaPengiriman::distinct()->pluck('kecamatan_asal');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('pengguna.alamat-penjemputan.create', compact('kecamatanAsal'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'keterangan_alamat' => 'nullable|string|max:255'
        ]);
        $userId = Session::get('user_id');
        $uid = 'ALT' . date('Ymd') . $userId . strtoupper(Str::random(5));

        Http::post('http://localhost:3001/alamat-penjemputan', [
            'uid' => $uid,
            'id_pengirim' => Session::get('user_uid'),
            'nama_pengirim' => $request->nama_pengirim,
            'no_hp' => $request->no_hp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'keterangan_alamat' => $request->keterangan_alamat,
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }


        $alamatPenjemputan = AlamatPenjemputan::where('uid', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$alamatPenjemputan) {
            return redirect()->route('alamat-penjemputan.index')
                ->with('error', 'Alamat penjemputan tidak ditemukan');
        }

        return view('pengguna.alamat-penjemputan.show', compact('alamatPenjemputan'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $data = AlamatPenjemputan::where('uid', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$data) {
            return redirect()->route('alamat-penjemputan.index')
                ->with('error', 'Alamat penjemputan tidak ditemukan');
        }

        return view('pengguna.alamat-penjemputan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'keterangan_alamat' => 'nullable|string|max:255'
        ]);

        Http::post('http://localhost:3001/alamat-penjemputan-edit', [
            'uid' => $id,
            'nama_pengirim' => $request->nama_pengirim,
            'no_hp' => $request->no_hp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'keterangan_alamat' => $request->keterangan_alamat,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return back()->with('success', 'Alamat penjemputan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        Http::post('http://localhost:3001/alamat-penjemputan-delete', [
            'uid' => $id
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Get alamat penjemputan for AJAX requests (untuk dropdown/select)
     */
    public function getAlamatPenjemputan()
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $alamatPenjemputan = AlamatPenjemputan::where('id_pengirim', $userId)
            ->select('id_alamat_penjemputan', 'nama_pengirim', 'alamat_lengkap', 'kecamatan', 'no_hp')
            ->orderBy('nama_pengirim')
            ->get();

        return response()->json($alamatPenjemputan);
    }

    /**
     * Get specific alamat penjemputan details for AJAX
     */
    public function getAlamatPenjemputanDetail($id)
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $alamatPenjemputan = AlamatPenjemputan::where('id_alamat_penjemputan', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$alamatPenjemputan) {
            return response()->json(['error' => 'Alamat penjemputan tidak ditemukan'], 404);
        }

        return response()->json($alamatPenjemputan);
    }
}