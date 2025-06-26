<?php

namespace App\Http\Controllers;

use App\Models\AlamatTujuan;
use App\Models\Pengguna;
use App\Models\ZonaPengiriman;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Str;

class AlamatTujuanController extends Controller
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

        $alamatTujuan = AlamatTujuan::where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        $kecamatanAsal = ZonaPengiriman::distinct()->pluck('kecamatan_asal');
        $kecamatanTujuan = ZonaPengiriman::distinct()->pluck('kecamatan_tujuan');
        return view('pengguna.alamat-tujuan.index', compact('alamatTujuan'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $userId = Session::get('user_uid');
        $kecamatanAsal = ZonaPengiriman::distinct()->pluck('kecamatan_asal');
        $kecamatanTujuan = ZonaPengiriman::distinct()->pluck('kecamatan_tujuan');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('pengguna.alamat-tujuan.create', compact('kecamatanTujuan'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'keterangan_alamat' => 'nullable|string|max:255'
        ]);
        $userId = Session::get('user_id');
        $uid = 'ALT' . date('Ymd') . $userId . strtoupper(Str::random(5));
        Http::post('http://localhost:3001/alamat-tujuan', [
            'uid' => $uid,
            'id_pengirim' => Session::get('user_uid'),
            'nama_penerima' => $request->nama_penerima,
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

        $alamatTujuan = AlamatTujuan::where('uid', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$alamatTujuan) {
            return redirect()->route('alamat-tujuan.index')
                ->with('error', 'Alamat tujuan tidak ditemukan');
        }

        return view('pengguna.alamat-tujuan.show', compact('alamatTujuan'));
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

        $data = AlamatTujuan::where('uid', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$data) {
            return redirect()->route('alamat-tujuan.index')
                ->with('error', 'Alamat tujuan tidak ditemukan');
        }

        return view('pengguna.alamat-tujuan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'keterangan_alamat' => 'nullable|string|max:255'
        ]);

        Http::post('http://localhost:3001/alamat-tujuan-edit', [
            'uid' => $id,
            'nama_penerima' => $request->nama_penerima,
            'no_hp' => $request->no_hp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'keterangan_alamat' => $request->keterangan_alamat,
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        Http::post('http://localhost:3001/alamat-tujuan-delete', [
            'uid' => $id
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Get alamat tujuan for AJAX requests (untuk dropdown/select)
     */
    public function getAlamatTujuan()
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $alamatTujuan = AlamatTujuan::where('id_pengirim', $userId)
            ->select('id_alamat_tujuan', 'nama_penerima', 'alamat_lengkap', 'kecamatan', 'no_hp')
            ->orderBy('nama_penerima')
            ->get();

        return response()->json($alamatTujuan);
    }

    /**
     * Get specific alamat tujuan details for AJAX
     */
    public function getAlamatTujuanDetail($id)
    {
        $userId = Session::get('user_uid');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $alamatTujuan = AlamatTujuan::where('id_alamat_tujuan', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$alamatTujuan) {
            return response()->json(['error' => 'Alamat tujuan tidak ditemukan'], 404);
        }

        return response()->json($alamatTujuan);
    }
}