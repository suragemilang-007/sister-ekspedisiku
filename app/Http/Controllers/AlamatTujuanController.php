<?php

namespace App\Http\Controllers;

use App\Models\AlamatTujuan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AlamatTujuanController extends Controller
{
    /**
     * Display a listing of the resource for logged in user
     */
    public function index()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $alamatTujuan = AlamatTujuan::where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('alamat-tujuan.index', compact('alamatTujuan'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('alamat-tujuan.create');
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $alamatTujuan = AlamatTujuan::where('id_alamat_tujuan', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$alamatTujuan) {
            return redirect()->route('alamat-tujuan.index')
                ->with('error', 'Alamat tujuan tidak ditemukan');
        }

        return view('alamat-tujuan.show', compact('alamatTujuan'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $alamatTujuan = AlamatTujuan::where('id_alamat_tujuan', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$alamatTujuan) {
            return redirect()->route('alamat-tujuan.index')
                ->with('error', 'Alamat tujuan tidak ditemukan');
        }

        return view('alamat-tujuan.edit', compact('alamatTujuan'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {

    }

    /**
     * Get alamat tujuan for AJAX requests (untuk dropdown/select)
     */
    public function getAlamatTujuan()
    {
        $userId = Session::get('user_id');

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
        $userId = Session::get('user_id');

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