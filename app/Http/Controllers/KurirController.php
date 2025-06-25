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

    public function tugas()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil tugas yang sedang aktif (belum selesai)
        $tugas_aktif = PenugasanKurir::with(['pengiriman.alamatTujuan'])
            ->where('id_kurir', $id_kurir)
            ->whereNotIn('status', ['SELESAI', 'DIBATALKAN'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kurir.tugas', compact('tugas_aktif'));
    }

    public function riwayat()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil riwayat pengiriman yang sudah selesai atau dibatalkan
        $riwayat_pengiriman = PenugasanKurir::with(['pengiriman.alamatTujuan'])
            ->where('id_kurir', $id_kurir)
            ->whereIn('status', ['SELESAI', 'DIBATALKAN'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kurir.riwayat', compact('riwayat_pengiriman'));
    }

    public function feedback()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil feedback untuk kurir ini
        $feedback = DB::table('feedback')
            ->join('pengiriman', 'feedback.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->join('penugasan_kurir', 'pengiriman.id_pengiriman', '=', 'penugasan_kurir.id_pengiriman')
            ->join('pengguna', 'feedback.id_pengguna', '=', 'pengguna.id_pengguna')
            ->where('penugasan_kurir.id_kurir', $id_kurir)
            ->select('feedback.*', 'pengguna.nama as nama_pengguna', 'pengiriman.nomor_resi')
            ->orderBy('feedback.created_at', 'desc')
            ->get();

        return view('kurir.feedback', compact('feedback'));
    }

    public function pengaturan()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');
        $kurir = Kurir::find($id_kurir);

        return view('kurir.pengaturan', compact('kurir'));
    }

    public function updateInfo(Request $request)
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        try {
            // Kirim data ke Kafka Producer
            $response = Http::post(env('KAFKA_PRODUCER_URL') . '/kurir/update-info', [
                'id_kurir' => Session::get('user_id'),
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
            ]);

            if ($response->successful()) {
                // Update session data
                Session::put('user_name', $request->nama);

                return redirect('/kurir/pengaturan')->with('success', 'Informasi akun berhasil diperbarui');
            } else {
                return redirect('/kurir/pengaturan')->with('error', 'Gagal memperbarui informasi akun');
            }
        } catch (\Exception $e) {
            Log::error('Error updating kurir info: ' . $e->getMessage());
            return redirect('/kurir/pengaturan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validasi input
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6',
            'konfirmasi_password' => 'required|string|same:password_baru',
        ]);

        try {
            // Kirim data ke Kafka Producer
            $response = Http::post(env('KAFKA_PRODUCER_URL') . '/kurir/update-password', [
                'id_kurir' => Session::get('user_id'),
                'password_lama' => $request->password_lama,
                'password_baru' => $request->password_baru,
            ]);

            if ($response->successful()) {
                return redirect('/kurir/pengaturan')->with('success', 'Password berhasil diperbarui');
            } else {
                return redirect('/kurir/pengaturan')->with('error', 'Gagal memperbarui password. Pastikan password lama benar.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating kurir password: ' . $e->getMessage());
            return redirect('/kurir/pengaturan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
