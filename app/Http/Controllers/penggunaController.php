<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
    public function index(Request $request)
    {
        $userId = Session::get('user_uid');

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

        ];

        // Recent shipments
        $recent_shipments = Pengiriman::with(['alamatTujuan', 'layananPaket'])
            ->where('id_pengirim', $userId)
            ->whereIn('status', ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR', 'DIKIRIM'])
            ->orderByRaw("FIELD(status, 'MENUNGGU KONFIRMASI','DIPROSES', 'DIBAYAR', 'DIKIRIM')")
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {

                return $item;
            });
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layananPaket', 'pelacakan'])
            ->where('id_pengirim', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Recent finished shipments in the last 3 days
        $recent_finish_shipment_3days = Pengiriman::with(['alamatTujuan', 'layananPaket'])
            ->where('id_pengirim', $userId)
            ->where('status', 'DITERIMA')
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'stats' => $stats,
                'recent_shipments' => $recent_shipments,
                'recent_finish_shipments' => $recent_finish_shipment_3days,
            ]);
        }

        return view('dashboard_pengirim.index', compact(
            'stats',
            'recent_shipments',
            'recent_finish_shipment_3days'
        ));
    }

    public function history(Request $request)
    {
        $userId = Session::get('user_uid');

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
            'rating_avg' => Feedback::whereHas('pengiriman', function ($q) use ($userId) {
                $q->where('id_pengirim', $userId);
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

    public function showDetail($nomor_resi)
    {
        try {
            $pengiriman = Pengiriman::with([
                'alamatTujuan',
                'zonaPengiriman.layananPaket',
                'kurir',
            ])->where('nomor_resi', $nomor_resi)->firstOrFail();

            $layanan = optional($pengiriman->zonaPengiriman)->layananPaket;
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

    public function createShipment()
    {
        $layanan = LayananPaket::all();
        return view('dashboard.create-shipment', compact('layanan'));
    }

    public function feedbackSidebar()
    {
        $userId = Session::get('user_uid');

        $stats = [
            'total_pengirimanDenganFeedback' => Pengiriman::where('id_pengirim', $userId)
                ->where('status', 'DITERIMA')
                ->whereDoesntHave('feedback')
                ->count(),
        ];
        return response()->json([
            'stats' => $stats,
        ]);
    }


}