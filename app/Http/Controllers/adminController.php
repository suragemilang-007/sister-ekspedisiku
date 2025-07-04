<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengiriman;
use App\Models\Pelacakan;
use App\Models\Feedback;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\LayananPaket;
use App\Models\AlamatTujuan;
use App\Models\ZonaPengiriman;
use App\Models\Kurir;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class adminController extends Controller
{
    public function index()
    {
        $userId = Session::get('uid');

        // Dashboard statistics
        $stats = [
            //['MENUNGGU KONFIRMASI','MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIPROSES', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN'];
            'total_pengiriman' => Pengiriman::where('id_pengirim', $userId)->count(),
            'pengiriman_baru' => Pengiriman::where('status', 'MENUNGGU KONFIRMASI')->count(),
            'total_kurir' => Kurir::count(),
            'pengiriman_selesai' => Pengiriman::where('status', 'DITERIMA')->count(),
            'jumlah_admin' => Pengguna::where('peran', 'admin')->count(),
        ];

        // Recent shipments
        $recent_shipments = Pengiriman::with(['alamatTujuan', 'layananPaket', 'pelacakan', 'pengguna', 'alamatPenjemputan']) // Exclude cancelled shipments
            ->orderBy('id_pengiriman', 'desc')
            ->take(5)
            ->get();
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layananPaket', 'pelacakan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin/dashboard.index', compact('stats', 'recent_shipments'));
    }
    public function editAdmin($id)
    {
        $pengguna = \DB::table('pengguna')->where('uid', $id)->first();
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function edit()
    {
        $userId = Session::get('uid');
        $pengguna = \DB::table('pengguna')->where('uid', $userId)->first();
        return view('admin.edit', compact('pengguna'));
    }

    public function list(Request $request)
    {
        $query = Pengguna::query();

        // Filter by search term
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Sort by selected criteria
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($request->sort_by, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc'); // Default sort
        }

        // Paginate results
        $admins = $query->where('peran', 'admin')->paginate(10);

        return view('admin.pengguna.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email|max:255', // Email harus unik
            'password' => 'required|string|min:8|confirmed',
            'tgl_lahir' => 'nullable|date',
            'nohp' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kelamin' => 'required|in:L,P',
        ]);

        try {
            // Hash password di sisi Laravel sebelum dikirim ke Kafka
            // Ini untuk memastikan password yang sampai ke consumer sudah ter-hash
            $hashedPassword = Hash::make($request->password);
            $uid = 'USER' . date('Ymd') . strtoupper(Str::random(6));
            $dataToProducer = [
                'uid' => $uid,
                'nama' => $request->nama,
                'email' => $request->email,
                'sandi_hash' => $hashedPassword, // Menggunakan sandi_hash
                'tgl_lahir' => $request->tgl_lahir,
                'nohp' => $request->nohp,
                'alamat' => $request->alamat,
                'kelamin' => $request->kelamin,
                'peran' => 'admin', // Pastikan ini adalah peran 'admin'
                'action_type' => 'add_user', // Indikator untuk consumer
                'timestamp' => now()->timestamp,
            ];

            // Kirim data ke JS Producer endpoint /pengguna/add
            $response = Http::timeout(5)->post('http://localhost:3001/pengguna/add', $dataToProducer);

            if ($response->successful()) {
                Log::info("Permintaan penambahan admin dikirim ke JS Producer untuk email: {$request->email}");
                return response()->json(['message' => 'Permintaan penambahan admin berhasil dikirim. Admin akan segera terdaftar.'], 200);
            } else {
                Log::error("Gagal mengirim permintaan penambahan admin ke JS Producer: " . $response->body() . " Status: " . $response->status());
                return response()->json(['message' => 'Gagal memproses permintaan penambahan admin. Silakan coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Pengecualian di storeAdmin: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.'], 500);
        }
    }


    public function updateUserInfo(Request $request)
    {
        $data = $request->only(['nama', 'email', 'tgl_lahir', 'nohp', 'alamat', 'kelamin']);
        $data['uid'] = $request->uid; // Use uid from request instead of session
        Http::post('http://localhost:3001/pengguna/update-info', $data);
        return response()->json(['status' => 'ok']);
    }

    public function updateUserPassword(Request $request)
    {
        $data = [
            'uid' => $request->uid,
            'password' => $request->password
        ];
        $response = Http::timeout(5)->post('http://localhost:3001/pengguna/update-password', $data);

        if ($response->successful()) {
            Log::info("Permintaan edit password pengguna dikirim ke JS Producer untuk UID: {$data['uid']}");
            return response()->json(['message' => 'Permintaan edit password berhasil dikirim.']);
        } else {
            Log::error("Gagal mengirim permintaan edit password ke JS Producer: " . $response->body() . " Status: " . $response->status());
            return response()->json(['message' => 'Gagal memproses permintaan edit password. Silakan coba lagi nanti.'], 500);
        }
    }

    public function deleteUser($uid)
    {
        // Cegah pengguna menghapus dirinya sendiri
        $currentUserId = Session::get('user_uid');
        if ($uid == $currentUserId) {
            return response()->json(['message' => 'Anda tidak bisa menghapus akun sendiri.'], 403);
        }

        try {
            $dataToProducer = [
                'uid' => $uid, // UID pengguna yang akan dihapus
                'action_type' => 'delete_user', // Aksi untuk Kafka consumer
                'timestamp' => now()->timestamp,
                'deleted_by_id' => $currentUserId, // UID penghapus
            ];

            // Kirim ke JS Producer di port 3001
            $response = Http::timeout(5)->post('http://localhost:3001/pengguna/delete', $dataToProducer);

            if ($response->successful()) {
                Log::info("Permintaan penghapusan pengguna dikirim ke JS Producer untuk UID: {$uid}");
                return response()->json(['message' => 'Permintaan penghapusan berhasil dikirim.']);
            } else {
                Log::error("Gagal mengirim permintaan penghapusan ke JS Producer: " . $response->body() . " Status: " . $response->status());
                return response()->json(['message' => 'Gagal memproses permintaan penghapusan. Silakan coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Pengecualian di deleteUser: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.'], 500);
        }
    }
}
