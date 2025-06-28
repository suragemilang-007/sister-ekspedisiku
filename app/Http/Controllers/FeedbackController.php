<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Pengiriman;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Str;

class FeedbackController extends Controller
{
    /**
     * Tampilkan halaman utama feedback
     * Menampilkan pengiriman yang belum diberi feedback dan sudah diberi feedback
     */
    public function index()
    {
        $userId = Session::get('user_uid');

        // Pengiriman yang sudah selesai (DITERIMA) tapi belum ada feedback
        // $pengirimanTanpaFeedback = Pengiriman::where('id_pengirim', $userId)
        //     ->where('status', 'DITERIMA')
        //     ->whereDoesntHave('feedback')
        //     ->with(['alamatTujuan', 'layananPaket', 'kurir'])
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        $pengirimanTanpaFeedback = Pengiriman::with(
            'alamatTujuan',
            'zonaPengiriman.layananPaket',
            'kurir'
        )
            ->where('id_pengirim', $userId)
            ->where('status', 'DITERIMA')
            ->whereDoesntHave('feedback')
            ->orderBy('created_at', 'desc')
            ->get();
        $pengirimanDenganFeedback = Pengiriman::with(
            'alamatTujuan',
            'zonaPengiriman.layananPaket',
            'kurir'
        )
            ->where('id_pengirim', $userId)
            ->where('status', 'DITERIMA')
            ->whereHas('feedback')
            ->orderBy('created_at', 'desc')
            ->get();

        // Pengiriman yang sudah diberi feedback
        // $pengirimanDenganFeedback = Pengiriman::where('id_pengirim', $userId)
        //     ->where('status', 'DITERIMA')
        //     ->whereHas('feedback')
        //     ->with(['alamatTujuan', 'layananPaket', 'kurir', 'feedback'])
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        return view('pengguna.feedback', compact('pengirimanTanpaFeedback', 'pengirimanDenganFeedback'));
    }

    /**
     * Tampilkan form untuk memberikan feedback
     */
    public function create($nomor_resi)
    {
        $userId = Session::get('user_uid');
        // Cek apakah pengiriman ini milik user dan sudah selesai
        $pengiriman = Pengiriman::where('id_pengirim', $userId)
            ->where('nomor_resi', $nomor_resi)
            ->where('status', 'DITERIMA')
            ->first();
        return view('pengguna.createFeedback', compact('nomor_resi', 'pengiriman'));
    }

    /**
     * Simpan feedback yang diberikan user
     */
    public function store(Request $request)
    {
        $userId = Session::get('user_id');
        $request->validate([
            'nomor_resi' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);
        $uid = 'FED' . $userId . date('Ymd') . strtoupper(Str::random(6));
        $userId = Session::get('user_uid');
        // Kirim ke Kafka producer (Node.js)
        Http::post('http://localhost:3001/feedback', [
            'uid' => $uid,
            'nomor_resi' => $request->nomor_resi,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Tampilkan detail feedback yang sudah diberikan
     */
    public function show($id_pengiriman)
    {
        $userId = Session::get('user_uid');

        // Ambil pengiriman dengan feedback
        $pengiriman = Pengiriman::where('id_pengiriman', $id_pengiriman)
            ->where('id_pengirim', $userId)
            ->where('status', 'DITERIMA')
            ->whereHas('feedback')
            ->with(['alamatTujuan', 'layananPaket', 'kurir', 'feedback'])
            ->first();

        if (!$pengiriman) {
            return redirect()->route('feedback.index')
                ->with('error', 'Pengiriman tidak ditemukan atau belum diberi feedback.');
        }

        return view('feedback.show', compact('pengiriman'));
    }



    /**
     * API untuk mendapatkan statistik feedback (opsional)
     */
    public function statistics()
    {
        $userId = Session::get('user_uid');

        $stats = [
            'total_pengiriman_selesai' => Pengiriman::where('id_pengirim', $userId)
                ->where('status', 'DITERIMA')
                ->count(),
            'total_feedback_diberikan' => Pengiriman::where('id_pengirim', $userId)
                ->where('status', 'DITERIMA')
                ->whereHas('feedback')
                ->count(),
            'total_belum_feedback' => Pengiriman::where('id_pengirim', $userId)
                ->where('status', 'DITERIMA')
                ->whereDoesntHave('feedback')
                ->count(),
            'rata_rata_rating' => Feedback::whereHas('pengiriman', function ($query) use ($userId) {
                $query->where('id_pengirim', $userId);
            })->avg('rating')
        ];

        return response()->json($stats);
    }
}