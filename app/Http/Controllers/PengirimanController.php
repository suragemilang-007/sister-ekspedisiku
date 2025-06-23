<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\AlamatTujuan;
use App\Models\AlamatPenjemputan;
use App\Models\LayananPaket;
use App\Models\ZonaPengiriman;
use App\Models\Pengguna;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PengirimanController extends Controller
{


    /**
     * Show the form for creating a new pengiriman
     */
    public function create()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get existing alamat penjemputan dan tujuan
        $alamatPenjemputan = AlamatPenjemputan::where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $alamatTujuan = AlamatTujuan::where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get layanan paket
        $layananPaket = LayananPaket::all();

        // Get unique kecamatan for zona pengiriman
        $kecamatanAsal = ZonaPengiriman::distinct()->pluck('kecamatan_asal');
        $kecamatanTujuan = ZonaPengiriman::distinct()->pluck('kecamatan_tujuan');

        return view('pengiriman.create', compact(
            'alamatPenjemputan',
            'alamatTujuan',
            'layananPaket',
            'kecamatanAsal',
            'kecamatanTujuan'
        ));
    }

    /**
     * Store a newly created pengiriman
     */
    public function store(Request $request)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'alamat_penjemputan_type' => 'required|in:existing,new',
            'alamat_tujuan_type' => 'required|in:existing,new',
            'id_layanan' => 'required|exists:layanan_paket,id_layanan',
            'kecamatan_asal' => 'required|string',
            'kecamatan_tujuan' => 'required|string',
            'catatan_opsional' => 'nullable|string',
            'foto_barang' => 'nullable|string', // base64 atau path
        ]);

        // Validasi alamat penjemputan
        if ($request->alamat_penjemputan_type === 'existing') {
            $request->validate([
                'id_alamat_penjemputan' => 'required|exists:alamat_penjemputan,id_alamat_penjemputan'
            ]);
            $idAlamatPenjemputan = $request->id_alamat_penjemputan;
        } else {
            return response()->json([
                'redirect' => route('alamat-penjemputan.create'),
                'message' => 'Silakan buat alamat penjemputan terlebih dahulu'
            ]);
        }

        // Validasi alamat tujuan
        if ($request->alamat_tujuan_type === 'existing') {
            $request->validate([
                'id_alamat_tujuan' => 'required|exists:alamat_tujuan,id_alamat_tujuan'
            ]);
            $idAlamatTujuan = $request->id_alamat_tujuan;
        } else {
            return response()->json([
                'redirect' => route('alamat-tujuan.create'),
                'message' => 'Silakan buat alamat tujuan terlebih dahulu'
            ]);
        }

        // Get zona pengiriman berdasarkan kecamatan dan layanan
        $zonaPengiriman = ZonaPengiriman::where('kecamatan_asal', $request->kecamatan_asal)
            ->where('kecamatan_tujuan', $request->kecamatan_tujuan)
            ->where('id_layanan', $request->id_layanan)
            ->with('layananPaket')
            ->first();

        if (!$zonaPengiriman) {
            return response()->json([
                'error' => 'Zona pengiriman tidak tersedia untuk rute dan layanan yang dipilih'
            ], 400);
        }

        // Generate nomor resi
        $nomorResi = 'EXP' . date('Ymd') . strtoupper(Str::random(6));

        // Calculate total biaya
        $totalBiaya = $zonaPengiriman->biaya_tambahan + $zonaPengiriman->layananPaket->harga_Dasar;

        // Send to Kafka producer
        $response = Http::post('http://localhost:3001/pengiriman', [
            'id_pengirim' => $userId,
            'id_alamat_tujuan' => $idAlamatTujuan,
            'id_alamat_penjemputan' => $idAlamatPenjemputan,
            'total_biaya' => $totalBiaya,
            'id_zona' => $zonaPengiriman->id_zona,
            'status' => 'MENUNGGU KONFIRMASI',
            'nomor_resi' => $nomorResi,
            'catatan_opsional' => $request->catatan_opsional,
            'foto_barang' => $request->foto_barang,
        ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengiriman berhasil dibuat',
                'nomor_resi' => $nomorResi,
                'total_biaya' => $totalBiaya
            ]);
        } else {
            return response()->json([
                'error' => 'Gagal membuat pengiriman'
            ], 500);
        }
    }

    /**
     * Display the specified pengiriman
     */
    public function show($id)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $pengiriman = Pengiriman::where('id_pengiriman', $id)
            ->where('id_pengirim', $userId)
            ->with([
                'alamatTujuan',
                'zonaPengiriman.layananPaket',
                'pelacakan' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'penugasanKurir.kurir',
                'feedback',
                'pembayaran'
            ])
            ->first();

        if (!$pengiriman) {
            return redirect()->route('pengiriman.index')
                ->with('error', 'Pengiriman tidak ditemukan');
        }

        return view('pengiriman.show', compact('pengiriman'));
    }

    /**
     * Get zona pengiriman and calculate biaya via AJAX
     */
    public function getZonaPengiriman(Request $request)
    {
        $request->validate([
            'kecamatan_asal' => 'required|string',
            'kecamatan_tujuan' => 'required|string',
            'id_layanan' => 'required|exists:layanan_paket,id_layanan'
        ]);

        $zonaPengiriman = ZonaPengiriman::where('kecamatan_asal', $request->kecamatan_asal)
            ->where('kecamatan_tujuan', $request->kecamatan_tujuan)
            ->where('id_layanan', $request->id_layanan)
            ->with('layananPaket')
            ->first();

        if (!$zonaPengiriman) {
            return response()->json([
                'error' => 'Zona pengiriman tidak tersedia untuk rute dan layanan yang dipilih'
            ], 404);
        }

        return response()->json([
            'id_zona' => $zonaPengiriman->id_zona,
            'biaya_zona' => $zonaPengiriman->biaya_zona,
            'asal' => $zonaPengiriman->kecamatan_asal,
            'tujuan' => $zonaPengiriman->kecamatan_tujuan,
            'layanan' => $zonaPengiriman->layananPaket->nama_layanan,
            'deskripsi_layanan' => $zonaPengiriman->layananPaket->deskripsi
        ]);
    }

    /**
     * Get alamat penjemputan details via AJAX
     */
    public function getAlamatPenjemputan($id)
    {
        $userId = Session::get('user_id');

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

    /**
     * Get alamat tujuan details via AJAX
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

    /**
     * Cancel pengiriman
     */
    public function cancel(Request $request, $id)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'keterangan_batal' => 'required|string|max:255'
        ]);

        // Check if pengiriman exists and belongs to user
        $pengiriman = Pengiriman::where('id_pengiriman', $id)
            ->where('id_pengirim', $userId)
            ->first();

        if (!$pengiriman) {
            return response()->json(['error' => 'Pengiriman tidak ditemukan'], 404);
        }

        // Check if pengiriman can be cancelled
        if (!in_array($pengiriman->status, ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR'])) {
            return response()->json([
                'error' => 'Pengiriman tidak dapat dibatalkan pada status: ' . $pengiriman->status
            ], 400);
        }

        // Send cancellation to Kafka producer
        Http::post('http://localhost:3001/pengiriman-cancel', [
            'id_pengiriman' => $id,
            'keterangan_batal' => $request->keterangan_batal,
            'status' => 'DIBATALKAN'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengiriman berhasil dibatalkan'
        ]);
    }

    /**
     * Get layanan paket details via AJAX
     */
    public function getLayananPaket($id)
    {
        $layanan = LayananPaket::find($id);

        if (!$layanan) {
            return response()->json(['error' => 'Layanan tidak ditemukan'], 404);
        }

        return response()->json($layanan);
    }

    /**
     * Get available kecamatan tujuan based on kecamatan asal and layanan
     */
    public function getKecamatanTujuan(Request $request)
    {
        $request->validate([
            'kecamatan_asal' => 'required|string',
            'id_layanan' => 'required|exists:layanan_paket,id_layanan'
        ]);

        $kecamatanTujuan = ZonaPengiriman::where('kecamatan_asal', $request->kecamatan_asal)
            ->where('id_layanan', $request->id_layanan)
            ->distinct()
            ->pluck('kecamatan_tujuan');

        return response()->json($kecamatanTujuan);
    }

    /**
     * Track pengiriman by nomor resi
     */
    public function track($nomorResi)
    {
        $pengiriman = Pengiriman::where('nomor_resi', $nomorResi)
            ->with([
                'alamatTujuan',
                'zonaPengiriman.layananPaket',
                'pelacakan' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                },
                'penugasanKurir.kurir'
            ])
            ->first();

        if (!$pengiriman) {
            return view('pengiriman.track', [
                'error' => 'Nomor resi tidak ditemukan'
            ]);
        }

        return view('pengiriman.track', compact('pengiriman'));
    }
}