<?php

namespace App\Http\Controllers;

use App\Models\ZonaPengiriman;
use Illuminate\Http\Request;

class ZonaPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zonaPengirimans = ZonaPengiriman::with('layananPaket')->paginate(10);
        return view('admin/zona.index', compact('zonaPengirimans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layananPakets = LayananPaket::all();
        return view('admin/zona.create', compact('layananPakets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanan_paket,id_layanan', // Pastikan id_layanan ada di tabel layanan_paket
            'nama_zona' => 'required|string|max:255',
            'kecamatan_asal' => 'required|string|max:255',
            'kecamatan_tujuan' => 'required|string|max:255',
            'biaya_tambahan' => 'required|numeric|min:0',
        ]);

        try {
            // Membuat entri baru di database
            ZonaPengiriman::create($request->all());
            return redirect()->route('admin/zona.index')->with('success', 'Zona pengiriman berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Menangani error jika terjadi
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan zona pengiriman: ' . $e->getMessage()]);
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(ZonaPengiriman $zonaPengiriman)
    {
        $zonaPengiriman->load('layananPaket');
        return view('admin/zona.show', compact('zonaPengiriman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ZonaPengiriman $zonaPengiriman)
    {
        $layananPakets = LayananPaket::all();
        return view('admin/zona.edit', compact('zonaPengiriman', 'layananPakets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ZonaPengiriman $zonaPengiriman)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanan_paket,id_layanan',
            'nama_zona' => 'required|string|max:255',
            'kecamatan_asal' => 'required|string|max:255',
            'kecamatan_tujuan' => 'required|string|max:255',
            'biaya_tambahan' => 'required|numeric|min:0',
        ]);

        try {
            $zonaPengiriman->update($request->all());
            return redirect()->route('admin/zona.index')->with('success', 'Zona pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui zona pengiriman: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZonaPengiriman $zonaPengiriman)
    {
        try {
            $zonaPengiriman->delete();
            return redirect()->route('admin/zona.index')->with('success', 'Zona pengiriman berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus zona pengiriman: ' . $e->getMessage()]);
        }
    }
}
