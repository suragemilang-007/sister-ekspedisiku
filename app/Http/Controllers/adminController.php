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

class adminController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');

        // Dashboard statistics
        $stats = [
            //['MENUNGGU KONFIRMASI','MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIPROSES', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN'];
            'total_pengiriman' => Pengiriman::where('id_pengirim', $userId)->count(),
            'pengiriman_baru' => Pengiriman::where('status', 'DIBAYAR')->count(),
            'total_kurir' => Kurir::count(),
            'pengiriman_selesai' => Pengiriman::where('status', 'DITERIMA')->count(),
            'jumlah_admin' => Pengguna::where('peran', 'admin')->count(),
        ];

        // Recent shipments
        $recent_shipments = Pengiriman::with(['alamatTujuan', 'layananPaket', 'pelacakan', 'pengguna'])
            ->where('status', '!=', 'DIBATALKAN')
            ->where('status', '!=', 'DITERIMA') // Exclude cancelled shipments
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $pengiriman = Pengiriman::with(['alamatTujuan', 'layananPaket', 'pelacakan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin/dashboard.index', compact('stats', 'recent_shipments'));
    }
    public function edit()
    {
        $userId = Session::get('user_id');
        $pengguna = \DB::table('pengguna')->where('id_pengguna', $userId)->first();
        return view('admin.edit', compact('pengguna'));
    }

}