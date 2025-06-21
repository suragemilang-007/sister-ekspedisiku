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
use Illuminate\Support\Facades\Session;

class penggunaController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');

        // Dashboard statistics
        $stats = [
            //['MENUNGGU KONFIRMASI','MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIPROSES', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN'];
            'total_pengiriman' => Pengiriman::where('id_pengirim', $userId)->count(),
            'pengiriman_aktif' => Pengiriman::where('id_pengirim', $userId)
                ->whereIn('status', ['MENUNGGU KONFIRMASI', 'DIBAYAR', 'DIPROSES', 'DIKIRIM'])
                ->count(),
            'pengiriman_selesai' => Pengiriman::where('id_pengirim', $userId)
                ->where('status', 'DITERIMA')
                ->count(),
            'total_biaya' => Pengiriman::where('id_pengirim', $userId)->sum('total_biaya'),
            'rating_rata' => Feedback::whereHas('pengiriman', function ($query) use ($userId) {
                $query->where('id_pengirim', $userId);
            })->avg('rating') ?: 0,
        ];

        // Recent shipments
        $recent_shipments = Pengiriman::with(['alamatTujuan', 'layananPaket'])
            ->where('id_pengirim', $userId)
            ->whereIn('status', ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR', 'DIKIRIM'])
            ->orderByRaw("FIELD(status, 'MENUNGGU KONFIRMASI','DIPROSES', 'DIBAYAR', 'DIKIRIM')")
            ->orderBy('created_at', 'desc')
            ->get();
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layananPaket', 'pelacakan'])
            ->where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard_pengirim.index', compact('stats', 'recent_shipments'));
    }
    public function history(Request $request)
    {
        $userId = Session::get('user_id');

        // Base query
        $query = Pengiriman::with(['alamatTujuan', 'layananPaket'])
            ->where('id_pengirim', $userId);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_resi', 'LIKE', "%{$search}%")
                    ->orWhereHas('alamatTujuan', function ($subQ) use ($search) {
                        $subQ->where('alamat_lengkap', 'LIKE', "%{$search}%")
                            ->orWhere('kecamatan', 'LIKE', "%{$search}%")
                            ->orWhere('kode_pos', 'LIKE', "%{$search}%")
                            ->orWhere('nama_penerima', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination with query parameters
        $recent_shipments = $query->paginate(10)->withQueryString();

        // Statistics (unchanged)
        $stats = [
            'total_pengiriman' => Pengiriman::where('id_pengirim', $userId)->count(),
            'pengiriman_aktif' => Pengiriman::where('id_pengirim', $userId)
                ->whereIn('status', ['MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIPROSES', 'DIKIRIM'])
                ->count(),
            'pengiriman_selesai' => Pengiriman::where('id_pengirim', $userId)
                ->where('status', 'DITERIMA')
                ->count(),
            'total_biaya' => Pengiriman::where('id_pengirim', $userId)->sum('total_biaya'),
            'rating_avg' => Feedback::whereHas('pengiriman', function ($query) use ($userId) {
                $query->where('id_pengirim', $userId);
            })->avg('rating') ?: 0,
        ];

        // Status options for filter dropdown
        $statusOptions = [
            'all' => 'Semua Status',
            'DIPROSES' => 'Diproses',
            'DIBAYAR' => 'Dibayar',
            'DIKIRIM' => 'Dikirim',
            'DITERIMA' => 'Diterima',
            'DIBATALKAN' => 'Dibatalkan'
        ];

        return view('dashboard_pengirim.history', compact(
            'stats',
            'recent_shipments',
            'statusOptions'
        ));
    }

    public function showDetail($id)
    {
        try {
            $pengiriman = Pengiriman::with([
                'alamatTujuan',
                'layananPaket',
                'kurir',
            ])->findOrFail($id);

            $layanan = LayananPaket::find($pengiriman->id_layanan);
            return response()->json([
                'status' => 'success',
                'data' => compact('pengiriman', 'layanan')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }
    }

    public function tracking()
    {
        $userId = Auth::id();
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layanan', 'pelacakan'])
            ->where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.tracking', compact('pengiriman'));
    }

    public function trackingDetail($id)
    {
        $userId = Auth::id();
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layanan', 'pelacakan', 'penugasanKurir.kurir'])
            ->where('id_pengirim', $userId)
            ->where('id_pengiriman', $id)
            ->firstOrFail();

        $tracking_history = Pelacakan::where('id_pengiriman', $id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.tracking-detail', compact('pengiriman', 'tracking_history'));
    }



    public function createShipment()
    {
        $layanan = LayananPaket::all();
        return view('dashboard.create-shipment', compact('layanan'));
    }

    public function storeShipment(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'no_hp' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'id_layanan' => 'required|exists:layanan_paket,id_layanan',
            'catatan_opsional' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Create alamat tujuan
            $alamat = AlamatTujuan::create([
                'nama_penerima' => $request->nama_penerima,
                'no_hp' => $request->no_hp,
                'alamat_lengkap' => $request->alamat_lengkap,
                'kecematan' => $request->kecamatan,
                'kode_pos' => $request->kode_pos,
                'telepon' => $request->telepon,
                'created_at' => now()
            ]);

            // Calculate shipping cost (simplified)
            $layanan = LayananPaket::find($request->id_layanan);
            $zona_biaya = ZonaPengiriman::where('id_layanan', $request->id_layanan)->first();
            $total_biaya = $layanan->harga_dasar + ($zona_biaya->biaya_zona ?? 0);

            // Generate resi number
            $nomor_resi = 'EXP' . date('Ymd') . sprintf('%06d', rand(1, 999999));

            // Create pengiriman
            $pengiriman = Pengiriman::create([
                'id_pengirim' => Auth::id(),
                'id_alamat_tujuan' => $alamat->id_alamat_tujuan,
                'total_biaya' => $total_biaya,
                'id_layanan' => $request->id_layanan,
                'status' => 'diproses',
                'nomor_resi' => $nomor_resi,
                'catatan_opsional' => $request->catatan_opsional,
                'created_at' => now()
            ]);

            // Create initial tracking
            Pelacakan::create([
                'id_pengiriman' => $pengiriman->id_pengiriman,
                'status' => 'Paket telah diterima dan sedang diproses',
                'lokasi' => 'Pusat Distribusi',
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('dashboard.payment', $pengiriman->id_pengiriman)
                ->with('success', 'Pengiriman berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function payment($id)
    {
        $userId = Auth::id();
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layanan', 'pembayaran'])
            ->where('id_pengirim', $userId)
            ->where('id_pengiriman', $id)
            ->firstOrFail();

        return view('dashboard.payment', compact('pengiriman'));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'metode' => 'required|in:transfer,e-wallet,tunai,kartu'
        ]);

        $userId = Auth::id();
        $pengiriman = Pengiriman::where('id_pengirim', $userId)
            ->where('id_pengiriman', $id)
            ->firstOrFail();

        // Create payment record
        Pembayaran::create([
            'id_pengiriman' => $id,
            'metode' => $request->metode,
            'status' => 'berhasil', // Simplified - in real app would integrate with payment gateway
            'jumlah_bayar' => $pengiriman->total_biaya,
            'waktu_bayar' => now()
        ]);

        // Update shipment status
        $pengiriman->update(['status' => 'menunggu kurir']);

        // Create notification
        Notifikasi::create([
            'id_pengguna' => $userId,
            'pesan' => "Pembayaran untuk resi {$pengiriman->nomor_resi} berhasil. Paket akan segera diproses.",
            'jenis' => 'in-app',
            'sent_at' => now()
        ]);

        return redirect()->route('dashboard.tracking')->with('success', 'Pembayaran berhasil! Paket Anda akan segera diproses.');
    }

    public function feedback()
    {
        $userId = Auth::id();
        $completed_shipments = Pengiriman::with(['alamatTujuan', 'feedback'])
            ->where('id_pengirim', $userId)
            ->where('status', 'Sampai')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.feedback', compact('completed_shipments'));
    }

    public function submitFeedback(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500'
        ]);

        $userId = Auth::id();
        $pengiriman = Pengiriman::where('id_pengirim', $userId)
            ->where('id_pengiriman', $id)
            ->where('status', 'Sampai')
            ->firstOrFail();

        // Check if feedback already exists
        $existing_feedback = Feedback::where('id_pengiriman', $id)->first();

        if ($existing_feedback) {
            $existing_feedback->update([
                'rating' => $request->rating,
                'komentar' => $request->komentar
            ]);
            $message = 'Feedback berhasil diperbarui!';
        } else {
            Feedback::create([
                'id_pengiriman' => $id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
                'created_at' => now()
            ]);
            $message = 'Feedback berhasil dikirim!';
        }

        return back()->with('success', $message);
    }

    public function notifications()
    {
        $userId = Auth::id();
        $notifications = Notifikasi::where('id_pengguna', $userId)
            ->orderBy('id_pengguna ', 'desc')
            ->paginate(15);

        return view('dashboard.notifications', compact('notifications'));
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'id_layanan' => 'required|exists:layanan_paket,id_layanan'
        ]);

        $layanan = LayananPaket::find($request->id_layanan);
        $zona_biaya = ZonaPengiriman::where('tujuan', 'like', '%' . $request->kecamatan . '%')
            ->where('id_layanan', $request->id_layanan)
            ->first();

        $total_biaya = $layanan->harga_dasar + ($zona_biaya->biaya_zona ?? 15000);

        return response()->json([
            'success' => true,
            'total_biaya' => $total_biaya,
            'formatted_biaya' => 'Rp ' . number_format($total_biaya, 0, ',', '.')
        ]);
    }
}