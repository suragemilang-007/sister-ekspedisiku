<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\AlamatTujuan;
use App\Models\AlamatPenjemputan;
use App\Models\LayananPaket;
use App\Models\ZonaPengiriman;
use App\Models\Pengguna;
use App\Models\PenugasanKurir;
use App\Models\Kurir;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Log;


class PengirimanController extends Controller
{


    /**
     * Show the form for creating a new pengiriman
     */
    public function create()
    {
        $userId = Session::get('user_uid');

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

        return view('pengguna.pengiriman.create', compact(
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
        $userId = Session::get('user_uid');

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
            'foto_barang' => 'nullable|string',
        ]);

        // Validasi alamat penjemputan
        if ($request->alamat_penjemputan_type === 'existing') {
            $request->validate([
                'id_alamat_penjemputan' => 'required|exists:alamat_penjemputan,uid'
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
                'id_alamat_tujuan' => 'required|exists:alamat_tujuan,uid'
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
        $nomorResi = 'RESI' . date('Ymd') . strtoupper(Str::random(6));

        // Calculate total biaya
        $totalBiaya = $zonaPengiriman->biaya_tambahan + $zonaPengiriman->layananPaket->harga_dasar;

        $base64 = base64_encode(file_get_contents($request->foto_barang));
        // Send to Kafka producer
        $response = Http::post('http://localhost:3001/pengiriman_add', [
            'id_pengirim' => $userId,
            'id_alamat_tujuan' => $idAlamatTujuan,
            'id_alamat_penjemputan' => $idAlamatPenjemputan,
            'total_biaya' => $totalBiaya,
            'id_zona' => $zonaPengiriman->id_zona,
            'status' => 'MENUNGGU KONFIRMASI',
            'nomor_resi' => $nomorResi,
            'catatan_opsional' => $request->catatan_opsional,
            'foto_barang' => $base64,
            'created_at' => now()->format('Y-m-d H:i:s'),
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
        $userId = Session::get('user_uid');

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
                'pembayaran',

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
            'biaya_zona' => $zonaPengiriman->biaya_tambahan,
            'asal' => $zonaPengiriman->kecamatan_asal,
            'tujuan' => $zonaPengiriman->kecamatan_tujuan,
            'layanan' => $zonaPengiriman->layananPaket->nama_layanan,
            'harga_dasar' => $zonaPengiriman->layananPaket->harga_dasar,
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


    public function editStatus($id)
    {

        $pengiriman = \DB::table('pengiriman')->where('id_pengiriman', $id)->first();
        return view('pengguna.test_websoket.edit', compact('pengiriman'));
    }

    public function updateStatus(Request $request)
    {
        $data = [
            'id_pengiriman' => $request->id_pengiriman,
            'status' => $request->status,
            'keterangan_batal' => $request->status === 'DIBATALKAN' ? $request->keterangan_batal : null,
        ];

        Http::post('http://localhost:3001/pengiriman/update-status-pengiriman', $data);
        return response()->json(['status' => 'ok']);
    }
    public function updateStatusselsai(Request $request)
    {
        $data = [
            'nomor_resi' => $request->id_pengiriman,
            'status' => $request->status,
            'keterangan_batal' => $request->status === 'DIBATALKAN' ? $request->keterangan_batal : null,
        ];

        Http::post('http://localhost:3001/pengiriman/update-status-pengiriman-selesai', $data);
        return response()->json(['status' => 'ok']);
    }


    public function pesananBaru(Request $request)
    {
        $kurirs = Kurir::where('status', 'AKTIF')
            ->whereDoesntHave('penugasan', function ($query) {
                $query->whereNotIn('status', ['SELESAI', 'DIBATALKAN']);
            })
            ->get();
        $statusList = ['MENUNGGU KONFIRMASI'];

        $query = Pengiriman::with(['pengguna', 'alamatPenjemputan', 'alamatTujuan', 'layananPaket'])
            ->whereIn('status', $statusList);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('alamatPenjemputan', function ($sub) use ($search) {
                    $sub->where('nama_pengirim', 'like', "%$search%");
                })->orWhereHas('alamatTujuan', function ($sub) use ($search) {
                    $sub->where('nama_penerima', 'like', "%$search%");
                })->orWhereHas('penugasanKurir.kurir', function ($sub) use ($search) {
                    $sub->where('nama', 'like', '%' . $search . '%');
                })->orWhere('status', 'like', "%$search%");
            });
        }

        // Sorting logic
        $sortBy = $request->get('sort_by', 'id_pengiriman');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'status') {
            $query->orderBy('status', $sortOrder);
        } else {
            $query->orderBy('id_pengiriman', 'desc'); // default or sort_by=id_pengiriman
        }

        $pesananBaru = $query->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact('pesananBaru', 'kurirs'));
    }


    public function semuaPesanan(Request $request)
    {
        // Get all pengiriman with kurir data
        $query = Pengiriman::with(['alamatPenjemputan', 'alamatTujuan', 'penugasanKurir.kurir']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('alamatPenjemputan', function ($sub) use ($search) {
                    $sub->where('nama_pengirim', 'like', '%' . $search . '%');
                })->orWhereHas('alamatTujuan', function ($sub) use ($search) {
                    $sub->where('nama_penerima', 'like', '%' . $search . '%');
                })->orWhereHas('penugasanKurir.kurir', function ($sub) use ($search) {
                    $sub->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $sortOrder = $request->get('sort_order', 'asc');
        $sortBy = $request->get('sort_by');

        if ($sortBy === 'nama_pengirim') {
            $query->join('alamat_penjemputan', 'pengiriman.id_alamat_penjemputan', '=', 'alamat_penjemputan.uid')
                ->orderBy('alamat_penjemputan.nama_pengirim', $sortOrder)
                ->select('pengiriman.*');
        } elseif ($sortBy === 'nama_penerima') {
            $query->join('alamat_tujuan', 'pengiriman.id_alamat_tujuan', '=', 'alamat_tujuan.uid')
                ->orderBy('alamat_tujuan.nama_penerima', $sortOrder)
                ->select('pengiriman.*');
        } elseif ($sortBy === 'nama') {
            $query->leftJoin('penugasan_kurir', 'pengiriman.id_pengiriman', '=', 'penugasan_kurir.id_pengiriman')
                ->leftJoin('kurir', 'penugasan_kurir.id_kurir', '=', 'kurir.id_kurir')
                ->orderBy('nama', $sortOrder)
                ->select('pengiriman.*');
        } elseif ($sortBy === 'status') {
            $query->orderBy('pengiriman.status', $sortOrder);
        } else {
            $query->orderBy('pengiriman.id_pengiriman', 'desc');
        }

        $semuaPesanan = $query->paginate(10);

        return view('admin.pesanan.semua', compact('semuaPesanan'));

    }

    public function assignKurir(Request $request)
    {
        try {
            $request->validate([
                'id_pengiriman' => 'required|exists:pengiriman,id_pengiriman',
                'id_kurir' => 'required|exists:kurir,id_kurir',
            ]);

            // Simpan ke database (via Kafka)
            $data = [
                'id_pengiriman' => $request->id_pengiriman,
                'id_kurir' => $request->id_kurir,
                'status' => 'MENUJU PENGIRIM',
                'timestamp' => now()->timestamp,
                'action_type' => 'assign_kurir'
            ];

            $response = Http::timeout(5)->post('http://localhost:3001/assign/add', $data);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Penugasan kurir berhasil dikirim ke Kafka.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim ke Kafka.']);
            }

        } catch (\Exception $e) {
            Log::error('Assign Kurir Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Kesalahan server.']);
        }
    }

    public function dibatalkan(Request $request, $id)
    {
        $request->validate([
            'keterangan_batal' => 'required|string|max:255'
        ]);

        try {
            $dataToKafka = [
                'id_pengiriman' => $id,
                'status' => 'DIBATALKAN',
                'keterangan_batal' => $request->keterangan_batal,
                'action_type' => 'batalkan_pengiriman'
            ];

            $response = Http::timeout(5)->post('http://localhost:3001/pengiriman/update-status-pengiriman', $dataToKafka);

            if ($response->successful()) {
                Log::info("Permintaan pembatalan pengiriman ID $id berhasil dikirim ke Kafka");
                return response()->json(['message' => 'Pesanan berhasil dibatalkan dan dikirim ke sistem.']);
            } else {
                Log::error("Gagal mengirim pembatalan pengiriman ke Kafka: " . $response->body());
                return response()->json(['message' => 'Gagal mengirim permintaan pembatalan.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception saat membatalkan pengiriman: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan internal.'], 500);
        }
    }

    // PesananController.php
    public function detailPengiriman($id)
    {
        $pesanan = Pengiriman::with(['alamatPenjemputan', 'alamatTujuan', 'kurir', 'layananPaket'])->findOrFail($id);

        return response()->json([
            'nomor_resi' => $pesanan->nomor_resi,
            'status' => $pesanan->status,
            'catatan' => $pesanan->catatan_opsional,
            'pengirim' => [
                'nama' => $pesanan->alamatPenjemputan->nama_pengirim ?? '-',
                'no_hp' => $pesanan->alamatPenjemputan->no_hp ?? '-',
                'alamat' => $pesanan->alamatPenjemputan->alamat_lengkap ?? '-',
                'kecamatan' => $pesanan->alamatPenjemputan->kecamatan ?? '-',
                'kode_pos' => $pesanan->alamatPenjemputan->kode_pos ?? '-',
            ],
            'penerima' => [
                'nama' => $pesanan->alamatTujuan->nama_penerima ?? '-',
                'no_hp' => $pesanan->alamatTujuan->no_hp ?? '-',
                'alamat' => $pesanan->alamatTujuan->alamat_lengkap ?? '-',
                'kecamatan' => $pesanan->alamatTujuan->kecamatan ?? '-',
                'kode_pos' => $pesanan->alamatTujuan->kode_pos ?? '-',
            ],
            'kurir' => [
                'nama' => $pesanan->kurir->nama ?? '-',
                'no_hp' => $pesanan->kurir->no_hp ?? '-',
            ],
            'biaya_pengiriman' => $pesanan->total_biaya ? 'Rp ' . number_format($pesanan->total_biaya, 0, ',', '.') : '-',
            'tanggal_pengiriman' => $pesanan->created_at->format('d F Y'),
            'layanan' => $pesanan->zonaPengiriman->layananPaket->nama_layanan ?? '-',

        ]);
    }



}