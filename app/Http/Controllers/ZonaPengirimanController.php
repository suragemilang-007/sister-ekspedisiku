<?php

namespace App\Http\Controllers;

use App\Models\ZonaPengiriman;
use App\Models\LayananPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ZonaPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil parameter pencarian dari request
        $search = $request->input('search');

        // Mengambil parameter pengurutan dari request dengan nilai default
        $sortBy = $request->query('sort_by', 'nama_zona'); // Default sort by nama_zona
        $sortOrder = $request->query('sort_order', 'asc'); // Default sort order ascending

        // Validasi kolom yang boleh diurutkan untuk mencegah SQL Injection
        $allowedSortColumns = ['nama_zona', 'kecamatan_asal', 'kecamatan_tujuan', 'biaya_tambahan'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            // Jika kolom tidak valid, kembalikan ke default
            $sortBy = 'nama_zona';
        }
        // Pastikan sortOrder hanya 'asc' atau 'desc'
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // Memulai query untuk mendapatkan data ZonaPengiriman dengan relasi layananPaket
        $query = ZonaPengiriman::with('layananPaket');

        // Menerapkan kondisi pencarian jika ada input 'search'
        $query->when($search, function ($q) use ($search) {
            $q->where('nama_zona', 'like', "%{$search}%")
                ->orWhere('kecamatan_asal', 'like', "%{$search}%")
                ->orWhere('kecamatan_tujuan', 'like', "%{$search}%")
                ->orWhereHas('layananPaket', function ($sq) use ($search) {
                    $sq->where('nama_layanan', 'like', "%{$search}%");
                });
        });

        // Menerapkan pengurutan setelah kondisi pencarian
        // Pengurutan ini akan selalu diterapkan, baik ada pencarian atau tidak.
        $query->orderBy($sortBy, $sortOrder);


        // Mengambil data dengan paginasi
        $zonaPengirimans = $query->paginate(10);

        // Menggunakan withQueryString untuk mempertahankan semua parameter (pencarian, pengurutan) di URL
        $zonaPengirimans->withQueryString();

        return view('admin.zona.index', compact('zonaPengirimans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layananPakets = LayananPaket::select('id_layanan', 'nama_layanan')->get(); // Ambil semua layanan paket untuk dropdown
        return view('admin.zona.create', compact('layananPakets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'id_layanan' => 'required|integer|min:1|exists:layanan_paket,id_layanan', // Memastikan id_layanan ada di tabel layanan_paket
            'nama_zona' => 'required|string|max:255',
            'kecamatan_asal' => 'required|string|max:255',
            'kecamatan_tujuan' => 'required|string|max:255',
            'biaya_tambahan' => 'required|numeric|min:0', // Menggunakan 'biaya_tambahan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal. Mohon periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity
        }

        try {
            // 2. Siapkan data untuk dikirim ke Kafka Producer
            $dataToProducer = [
                'id_layanan' => (int)$request->id_layanan,
                'nama_zona' => $request->nama_zona,
                'kecamatan_asal' => $request->kecamatan_asal,
                'kecamatan_tujuan' => $request->kecamatan_tujuan,
                'biaya_tambahan' => (float)$request->biaya_tambahan, // Pastikan tipe data float untuk decimal
                'action_type' => 'create_zona', // Indikator untuk consumer
            ];

            // 3. Kirim data ke JS Producer endpoint (misal: /zona/create)
            $response = Http::timeout(5)->post('http://localhost:3001/zona/create', $dataToProducer);

            // 4. Tangani respons dari Producer
            if ($response->successful()) {
                Log::info("Permintaan penambahan zona pengiriman dikirim ke JS Producer: " . $request->nama_zona);
                return response()->json(['message' => 'Zona pengiriman berhasil dikirim untuk diproses.']);
            } else {
                Log::error("Gagal mengirim permintaan penambahan zona ke JS Producer. Status: " . $response->status() . " Body: " . $response->body());
                return response()->json(['message' => 'Gagal memproses permintaan penambahan zona. Silakan coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Pengecualian di ZonaController@store: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return response()->json(['message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.'], 500);
        }
    }

    // ... (method-method lain, misalnya untuk menampilkan daftar zona)

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
    public function edit($id)
    {
        // Ambil data zona berdasarkan ID
        $zonaPengiriman = ZonaPengiriman::findOrFail($id);

        // Ambil semua layanan paket (untuk dropdown)
        $layananPakets = LayananPaket::select('id_layanan', 'nama_layanan')->get();


        // Kirim ke view
        return view('admin.zona.edit', compact('zonaPengiriman', 'layananPakets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ZonaPengiriman $zonaPengiriman)
    {
        // Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'id_layanan' => 'required|integer|min:1|exists:layanan_paket,id_layanan', // Memastikan id_layanan ada di tabel layanan_paket
            'nama_zona' => 'required|string|max:255',
            'kecamatan_asal' => 'required|string|max:255',
            'kecamatan_tujuan' => 'required|string|max:255',
            'biaya_tambahan' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal. Mohon periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Siapkan data untuk dikirim ke Kafka Producer
            $dataToProducer = [
                'id_zona' => $request->id_zona,
                'id_layanan' => (int)$request->id_layanan,
                'nama_zona' => $request->nama_zona,
                'kecamatan_asal' => $request->kecamatan_asal,
                'kecamatan_tujuan' => $request->kecamatan_tujuan,
                'biaya_tambahan' => (float)$request->biaya_tambahan,
                'action_type' => 'update_zona',
            ];

            // Kirim data ke JS Producer endpoint (misal: /zona/update)
            $response = Http::timeout(5)->post('http://localhost:3001/zona/update', $dataToProducer);

            if ($response->successful()) {
                Log::info("Permintaan update zona pengiriman dikirim ke JS Producer: " . $zonaPengiriman->nama_zona);
                return response()->json(['message' => 'Zona pengiriman berhasil dikirim untuk diperbarui.']);
            } else {
                Log::error("Gagal mengirim permintaan update zona ke JS Producer. Status: " . $response->status() . " Body: " . $response->body());
                return response()->json(['message' => 'Gagal memproses permintaan update zona. Silakan coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Pengecualian di ZonaController@update: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return response()->json(['message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Siapkan data untuk dikirim ke Kafka Producer
            $dataToProducer = [
                'id_zona' => $id,
                'action_type' => 'delete_zona',
            ];

            // Kirim data ke JS Producer endpoint (misal: /zona/delete)
            $response = Http::timeout(5)->post('http://localhost:3001/zona/delete', $dataToProducer);

            if ($response->successful()) {
                Log::info("Permintaan penghapusan zona pengiriman dikirim ke JS Producer: " . $zonaPengiriman->nama_zona);
                return response()->json(['message' => 'Zona pengiriman berhasil dikirim untuk dihapus.']);
            } else {
                Log::error("Gagal mengirim permintaan penghapusan zona ke JS Producer. Status: " . $response->status() . " Body: " . $response->body());
                return response()->json(['message' => 'Gagal memproses permintaan penghapusan zona. Silakan coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Pengecualian di ZonaController@destroy: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return response()->json(['message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.'], 500);
        }
    }
}
