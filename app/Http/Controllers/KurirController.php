<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengiriman;
use App\Models\PenugasanKurir;
use App\Models\Kurir;
use App\Models\Pelacakan;
use App\Services\KafkaProducerService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class KurirController extends Controller
{
    private KafkaProducerService $kafkaProducer;

    public function __construct(KafkaProducerService $kafkaProducer)
    {
        $this->kafkaProducer = $kafkaProducer;
    }

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
                ->whereIn('status', ['MENUJU PENGIRIM', 'DITERIMA KURIR', 'DIANTAR'])
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
            ->whereNotIn('status', ['DITERIMA', 'DIBATALKAN'])
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

    public function showUpdateForm($id_penugasan)
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil data penugasan dengan relasi
        $penugasan = PenugasanKurir::with([
            'pengiriman.alamatTujuan',
            'pengiriman.pelacakan' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])
            ->where('id_penugasan', $id_penugasan)
            ->where('id_kurir', $id_kurir)
            ->firstOrFail();

        return view('kurir.update', compact('penugasan'));
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
                'status' => 'required|string|in:MENUNGGU KONFIRMASI,DIPROSES,DIBAYAR,DIKIRIM,DITERIMA,DIBATALKAN',
                'catatan' => 'nullable|string|max:500',
            ]);

            $id_kurir = Session::get('user_id');

            // Ambil data penugasan
            $penugasan = PenugasanKurir::with(['pengiriman.pengguna'])
                ->where('id_penugasan', $request->id_penugasan)
                ->where('id_kurir', $id_kurir)
                ->first();

            if (!$penugasan) {
                return response()->json(['success' => false, 'message' => 'Penugasan tidak ditemukan'], 404);
            }

            try {
                $dataPenugasan = [
                    'id_kurir' => $id_kurir,
                    'id_pengiriman' => $penugasan->pengiriman->id_pengiriman,
                    'id_penugasan' => $penugasan->id_penugasan,
                    'status' => $request->status,
                    'catatan' => $request->catatan ?? null,
                    'action_type' => 'update_penugasan_kurir',
                    'status_tugas' => $request->input('status_tugas')
                ];
                // Logging sebelum request
                \Log::info('[Kafka] Mengirim update penugasan_kurir ke Node.js', $dataPenugasan);
                // Kirim ke endpoint Node.js (Kafka Producer)
                $response = \Http::post('http://localhost:3003/kurir/update-status', $dataPenugasan);
                // Logging response
                \Log::info('[Kafka] Response update penugasan_kurir', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if ($response->successful()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pengiriman berhasil dibatalkan'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal mengirim data ke sistem'
                    ], 500);
                }
            } catch (\Exception $e) {
                \Log::error('Exception saat mengirim update penugasan_kurir ke Kafka Node.js: ' . $e->getMessage(), [
                    'id_penugasan' => $penugasan->id_penugasan ?? null,
                    'status' => $request->status ?? null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi Kafka Node.js: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
                ->whereIn('status', ['MENUJU PENGIRIM', 'DITERIMA KURIR', 'DIANTAR'])
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

        // Mengambil semua tugas kurir
        $tugas = PenugasanKurir::with(['pengiriman.alamatTujuan'])
            ->where('id_kurir', $id_kurir)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kurir.tugas', compact('tugas'));
    }

    public function riwayat()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Ambil pengiriman yang sudah DITERIMA atau DIBATALKAN dan penugasannya milik kurir ini
        $riwayat = Pengiriman::with(['alamatTujuan', 'penugasanKurir'])
            ->whereHas('penugasanKurir', function ($q) use ($id_kurir) {
                $q->where('id_kurir', $id_kurir);
            })
            ->whereIn('status', ['DITERIMA', 'DIBATALKAN'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kurir.riwayat', compact('riwayat'));
    }

    public function feedback()
    {
        // Pastikan user yang login adalah kurir
        if (Session::get('user_role') !== 'kurir') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_kurir = Session::get('user_id');

        // Mengambil feedback untuk pengiriman yang ditangani kurir
        $feedback = \App\Models\Feedback::with(['pengiriman.alamatTujuan'])
            ->whereHas('pengiriman.penugasanKurir', function ($query) use ($id_kurir) {
                $query->where('id_kurir', $id_kurir);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        try {
            // Pastikan user yang login adalah kurir
            if (Session::get('user_role') !== 'kurir') {
                return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
            }

            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'nomor_telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
            ]);

            $id_kurir = Session::get('user_id');
            $kurir = Kurir::find($id_kurir);

            if (!$kurir) {
                return redirect()->back()->with('error', 'Data kurir tidak ditemukan');
            }

            // Update informasi kurir
            $kurir->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat' => $request->alamat,
            ]);

            // Update session
            Session::put('user_name', $request->nama);

            return redirect()->back()->with('success', 'Informasi berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating kurir info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
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

            $id_kurir = Session::get('user_id');
            $kurir = Kurir::find($id_kurir);

            if (!$kurir) {
                return redirect()->back()->with('error', 'Data kurir tidak ditemukan');
            }

            // Cek password lama
            if (!Hash::check($request->password_lama, $kurir->sandi_hash)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai');
            }

            // Update password
            $kurir->update([
                'sandi_hash' => Hash::make($request->password_baru),
            ]);

            return redirect()->back()->with('success', 'Password berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating kurir password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function listKurir(Request $request)
    {
        $query = Kurir::query();

        // SEARCH
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('nohp', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%");
            });
        }

        // SORTING
        $allowedSorts = ['nama', 'email', 'status']; // kolom yang boleh disort
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'id_kurir';
        $sortOrder = in_array($request->get('sort_order'), ['asc', 'desc']) ? $request->get('sort_order') : 'desc';

        $kurirs = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return view('admin.kurir.index', compact('kurirs'));
    }

    public function edit($id)
    {
        $kurir = Kurir::findOrFail($id);
        return view('admin.kurir.edit', compact('kurir'));
    }

    public function profileUpdate(Request $request)
    {
        $data = $request->only(['id_kurir', 'nama', 'email', 'nohp', 'alamat', 'status']);

        Http::timeout(5)->post('http://localhost:3001/kurir/update-profile', $data);
        return response()->json(['status' => 'ok']);
    }

    public function passwordChange(Request $request)
    {
        $data = [
            'id_kurir' => $request->id_kurir,
            'password' => $request->password
        ];

        Http::timeout(5)->post('http://localhost:3001/kurir/update-password', $data);
        return response()->json(['status' => 'ok']);
    }

    public function createKurir()
    {
        return view('admin.kurir.create');
    }

    public function storeKurir(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:kurir,email',
            'password' => 'required|string|min:8|confirmed',
            'nohp' => 'required|string|max:255',
            'alamat' => 'required|string',
            'status' => 'required|in:AKTIF,NONAKTIF',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nohp' => $validated['nohp'],
            'alamat' => $validated['alamat'],
            'status' => $validated['status'],
            'sandi_hash' => bcrypt($validated['password']),
        ];

        // Kirim ke Kafka melalui API Node.js
        Http::timeout(5)->post('http://localhost:3001/kurir/add', $data);

        return response()->json(['message' => 'Data kurir berhasil dikirim.']);
    }

    public function deleteKurir($id)
    {
        // Validasi dan ambil data kurir yang akan dihapus
        $kurir = Kurir::findOrFail($id);

        try {
            $data = [
                'id_kurir' => $kurir->id_kurir,
                'action_type' => 'delete_kurir',
            ];

            $response = Http::timeout(5)->post('http://localhost:3001/kurir/delete', $data);

            if ($response->successful()) {
                Log::info('✅ Permintaan penghapusan kurir berhasil dikirim ke Kafka untuk ID: ' . $kurir->id_kurir);
                return response()->json(['message' => 'Permintaan penghapusan kurir telah dikirim. Kurir akan segera dihapus.'], 200);
            } else {
                Log::error('❌ Gagal mengirim permintaan penghapusan kurir ke Kafka: ' . $response->body());
                return response()->json(['message' => 'Gagal menghapus kurir.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('❌ Error saat menghapus kurir: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus kurir.'], 500);
        }
    }

    /**
     * Konfirmasi pengiriman diterima oleh kurir (update tanggal_sampai, catatan_opsional, foto_bukti_sampai)
     */
    public function konfirmasiDiterima(Request $request)
    {
        try {
            if (Session::get('user_role') !== 'kurir') {
                return response()->json(['status' => 'forbidden', 'message' => 'Akses ditolak'], 403);
            }

            $request->validate([
                'id_pengiriman' => 'required|numeric',
                'tanggal_sampai' => 'required|date',
                'catatan_opsional' => 'nullable|string|max:500',
                'foto_bukti_sampai' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            ]);

            $pengiriman = Pengiriman::find($request->id_pengiriman);
            if (!$pengiriman) {
                return response()->json(['status' => 'not_found', 'message' => 'Pengiriman tidak ditemukan'], 404);
            }

            // Handle file upload
            if ($request->hasFile('foto_bukti_sampai')) {
                $file = $request->file('foto_bukti_sampai');
                $filename = 'bukti_sampai_' . $pengiriman->id_pengiriman . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti_sampai', $filename, 'public');
                $pengiriman->foto_bukti_sampai = $path;
            }

            $pengiriman->tanggal_sampai = $request->tanggal_sampai;
            $pengiriman->catatan_opsional = $request->catatan_opsional;
            $pengiriman->status = 'DITERIMA';
            $pengiriman->save();

            return response()->json(['status' => 'ok', 'message' => 'Pengiriman berhasil dikonfirmasi diterima']);
        } catch (\Exception $e) {
            \Log::error('Gagal konfirmasi diterima: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

}