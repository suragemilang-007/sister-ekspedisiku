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

            // Update status di database
            DB::beginTransaction();
            try {
                // Update status penugasan
                $penugasan->update([
                    'status' => $request->status,
                    'catatan' => $request->catatan,
                    'updated_at' => now()
                ]);

                // Update status pengiriman
                $penugasan->pengiriman->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

                // Tambah record pelacakan
                Pelacakan::create([
                    'id_pengiriman' => $penugasan->pengiriman->id_pengiriman,
                    'status' => $request->status,
                    'lokasi' => 'Dalam pengiriman',
                    'keterangan' => $request->catatan ?? 'Status diperbarui oleh kurir',
                    'id_pengguna' => $id_kurir, // gunakan id kurir (integer) yang sedang login
                    'created_at' => now()
                ]);

                DB::commit();

                // Kirim ke Kafka
                $success = $this->kafkaProducer->sendStatusUpdate(
                    $penugasan->pengiriman->nomor_resi,
                    $request->status,
                    now()->format('Y-m-d'),
                    $request->catatan
                );

                if (!$success) {
                    Log::warning('Failed to send status update to Kafka', [
                        'id_penugasan' => $request->id_penugasan,
                        'status' => $request->status
                    ]);
                }

                return response()->json([
                    'success' => true, 
                    'message' => 'Status berhasil diperbarui',
                    'data' => [
                        'id_penugasan' => $penugasan->id_penugasan,
                        'nomor_resi' => $penugasan->pengiriman->nomor_resi,
                        'status' => $request->status,
                        'tanggal' => now()->format('Y-m-d')
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
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

        // Mengambil riwayat tugas yang sudah selesai
        $riwayat = PenugasanKurir::with(['pengiriman.alamatTujuan'])
            ->where('id_kurir', $id_kurir)
            ->whereIn('status', ['SELESAI', 'DIBATALKAN'])
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

}
