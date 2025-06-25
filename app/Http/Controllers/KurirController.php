<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengiriman;
use App\Models\PenugasanKurir;
use App\Models\Kurir;
use App\Models\Pelacakan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class KurirController extends Controller
{
    public function dashboard()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil statistik untuk dashboard
        $stats = [
            'total_tugas' => PenugasanKurir::where('id_kurir', $id_kurir)->count(),
            'sedang_dikirim' => PenugasanKurir::where('id_kurir', $id_kurir)
                ->whereIn('status', ['MENUJU PENGIRIM', 'DITERIMA KURIRI', 'DIANTAR'])
                ->count(),
            'selesai' => PenugasanKurir::where('id_kurir', $id_kurir)
                ->where('status', 'SELESAI')
                ->count(),
            'dibatalkan' => PenugasanKurir::where('id_kurir', $id_kurir)
                ->where('status', 'DIBATALKAN')
                ->count(),
        ];

        // Mengambil tugas terbaru
        $tugas_terbaru = PenugasanKurir::with(['pengiriman.alamatTujuan'])
            ->where('id_kurir', $id_kurir)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('kurir.dashboard', compact('stats', 'tugas_terbaru'));
    }

    public function detail($id_penugasan)
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil data penugasan dengan relasi
        $penugasan = PenugasanKurir::with([
            'pengiriman.alamatTujuan',
            'pengiriman.alamatPenjemputan',
            'pengiriman.pelacakan' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])
            ->where('id_penugasan', $id_penugasan)
            ->where('id_kurir', $id_kurir)
            ->firstOrFail();

        return view('kurir.detail', compact('penugasan'));
    }

    public function updateStatus(Request $request)
    {
        try {
            // Pastikan user yang login adalah kurir
            if (Session::get('user_role') !== 'kurir') {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
            }

            // Validasi input
            $request->validate([
                'id_penugasan' => 'required|numeric',
                'status' => 'required|string',
                'catatan' => 'nullable|string',
            ]);

            // Kirim data ke Kafka Producer
            $response = Http::post(env('KAFKA_PRODUCER_URL') . '/kurir/update-status', [
                'id_penugasan' => $request->id_penugasan,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'id_kurir' => Session::get('user_id'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui status'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error updating kurir status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function dashboardData()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $id_kurir = Session::get('user_id');

        // Mengambil statistik untuk dashboard
        $stats = [
            'total_tugas' => PenugasanKurir::where('id_kurir', $id_kurir)->count(),
            'sedang_dikirim' => PenugasanKurir::where('id_kurir', $id_kurir)
                ->whereIn('status', ['MENUJU PENGIRIM', 'DITERIMA KURIRI', 'DIANTAR'])
                ->count(),
            'selesai' => PenugasanKurir::where('id_kurir', $id_kurir)
                ->where('status', 'SELESAI')
                ->count(),
            'dibatalkan' => PenugasanKurir::where('id_kurir', $id_kurir)
                ->where('status', 'DIBATALKAN')
                ->count(),
        ];

        return response()->json(['stats' => $stats]);
    }
}
