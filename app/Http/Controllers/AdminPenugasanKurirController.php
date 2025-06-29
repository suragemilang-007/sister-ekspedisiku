<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Kurir;
use App\Models\PenugasanKurir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminPenugasanKurirController extends Controller
{
    public function index()
    {
        $pengiriman = Pengiriman::with(['pengirim', 'alamatTujuan', 'alamatPenjemputan', 'zonaPengiriman', 'penugasanKurir.kurir'])
            ->whereIn('status', ['MENUNGGU KONFIRMASI'])
            ->orderBy('created_at', 'desc')
            ->get();

        $kurirAktif = Kurir::where('status', 'AKTIF')->get();

        return view('admin.pesanan.penugasan', compact('pengiriman', 'kurirAktif'));
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create($id_pengiriman)
    {
        $pengiriman = Pengiriman::with(['pengirim', 'alamatTujuan', 'alamatPenjemputan', 'zonaPengiriman'])
            ->where('id_pengiriman', $id_pengiriman)
            ->where('status', 'MENUNGGU KONFIRMASI')
            ->first();

        if (!$pengiriman) {
            return redirect()->route('admin.penugasan-kurir.index')
                ->with('error', 'Pengiriman tidak ditemukan atau sudah diproses');
        }

        $kurirAktif = Kurir::where('status', 'AKTIF')->get();

        return view('admin.penugasan-kurir.create', compact('pengiriman', 'kurirAktif'));
    }

    /**
     * Store a newly created assignment in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pengiriman' => 'required|exists:pengiriman,id_pengiriman',
            'id_kurir' => 'required|exists:kurir,id_kurir',
            'catatan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah pengiriman masih menunggu konfirmasi
        $pengiriman = Pengiriman::where('id_pengiriman', $request->id_pengiriman)
            ->where('status', 'MENUNGGU KONFIRMASI')
            ->first();

        if (!$pengiriman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengiriman tidak ditemukan atau sudah diproses'
            ], 404);
        }

        // Cek apakah kurir aktif
        $kurir = Kurir::where('id_kurir', $request->id_kurir)
            ->where('status', 'AKTIF')
            ->first();

        if (!$kurir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurir tidak ditemukan atau tidak aktif'
            ], 404);
        }

        // Cek apakah pengiriman sudah memiliki penugasan
        $existingAssignment = PenugasanKurir::where('id_pengiriman', $request->id_pengiriman)->first();
        if ($existingAssignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengiriman sudah memiliki penugasan kurir'
            ], 400);
        }

        try {
            // Kirim data ke Kafka producer untuk penugasan kurir dan update status pengiriman
            $response = Http::post('http://localhost:3001/penugasan-kurir', [
                'id_pengiriman' => $request->id_pengiriman,
                'id_kurir' => $request->id_kurir,
                'status_penugasan' => 'MENUJU PENGIRIM',
                'status_pengiriman' => 'DIPROSES', // Update status pengiriman
                'catatan' => $request->catatan,
                'assigned_by' => Session::get('admin_id', 'system')
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Kurir berhasil ditugaskan dan status pengiriman diupdate ke DIPROSES'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim data ke sistem'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified assignment
     */
    public function show($id_pengiriman)
    {
        $pengiriman = Pengiriman::with([
            'pengirim',
            'alamatTujuan',
            'alamatPenjemputan',
            'zonaPengiriman',
            'penugasanKurir.kurir'
        ])
            ->where('id_pengiriman', $id_pengiriman)
            ->first();

        if (!$pengiriman) {
            return redirect()->route('admin.penugasan-kurir.index')
                ->with('error', 'Pengiriman tidak ditemukan');
        }

        return view('admin.penugasan-kurir.show', compact('pengiriman'));
    }

    /**
     * Show the form for editing the specified assignment
     */
    public function edit($id_pengiriman)
    {
        $pengiriman = Pengiriman::with([
            'pengirim',
            'alamatTujuan',
            'alamatPenjemputan',
            'zonaPengiriman',
            'penugasanKurir.kurir'
        ])
            ->where('id_pengiriman', $id_pengiriman)
            ->first();

        if (!$pengiriman || !$pengiriman->penugasanKurir) {
            return redirect()->route('admin.penugasan-kurir.index')
                ->with('error', 'Pengiriman atau penugasan tidak ditemukan');
        }

        $kurirAktif = Kurir::where('status', 'AKTIF')->get();

        return view('admin.penugasan-kurir.edit', compact('pengiriman', 'kurirAktif'));
    }

    /**
     * Update the specified assignment in storage
     */
    public function update(Request $request, $id_pengiriman)
    {
        $validator = Validator::make($request->all(), [
            'id_kurir' => 'required|exists:kurir,id_kurir',
            'status' => 'required|in:MENUJU PENGIRIM,DITERIMA KURIR,DIANTAR,DITERIMA,DALAM_PENGIRIMAN,SELESAI,DIBATALKAN',
            'catatan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengiriman = Pengiriman::with('penugasanKurir')
            ->where('id_pengiriman', $id_pengiriman)
            ->first();

        if (!$pengiriman || !$pengiriman->penugasanKurir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengiriman atau penugasan tidak ditemukan'
            ], 404);
        }

        // Cek apakah kurir aktif
        $kurir = Kurir::where('id_kurir', $request->id_kurir)
            ->where('status', 'AKTIF')
            ->first();

        if (!$kurir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurir tidak ditemukan atau tidak aktif'
            ], 404);
        }

        try {
            // Kirim data ke Kafka producer
            $response = Http::post('http://localhost:3001/penugasan-kurir-update', [
                'id_penugasan' => $pengiriman->penugasanKurir->id_penugasan,
                'id_kurir' => $request->id_kurir,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'updated_by' => Session::get('admin_id', 'system')
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Penugasan kurir berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim data ke sistem'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel pengiriman
     */
    public function cancel(Request $request, $id_pengiriman)
    {
        $validator = Validator::make($request->all(), [
            'keterangan_batal' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengiriman = Pengiriman::where('id_pengiriman', $id_pengiriman)
            ->whereIn('status', ['MENUNGGU KONFIRMASI', 'DIPROSES'])
            ->first();

        if (!$pengiriman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengiriman tidak ditemukan atau tidak dapat dibatalkan'
            ], 404);
        }

        try {
            // Kirim data ke Kafka producer
            $response = Http::post('http://localhost:3001/pengiriman-cancel', [
                'id_pengiriman' => $id_pengiriman,
                'keterangan_batal' => $request->keterangan_batal,
                'cancelled_by' => Session::get('admin_id', 'system')
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
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pengiriman list for AJAX
     */
    public function getPengirimanList(Request $request)
    {
        $query = Pengiriman::with(['pengirim', 'alamatTujuan', 'alamatPenjemputan', 'zonaPengiriman']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['MENUNGGU KONFIRMASI', 'DIPROSES']);
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan nomor resi
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_resi', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pengirim', function ($q2) use ($request) {
                        $q2->where('nama', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $pengiriman = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data' => $pengiriman->items(),
            'pagination' => [
                'current_page' => $pengiriman->currentPage(),
                'last_page' => $pengiriman->lastPage(),
                'per_page' => $pengiriman->perPage(),
                'total' => $pengiriman->total()
            ]
        ]);
    }

    /**
     * Get kurir list for AJAX
     */
    public function getKurirList(Request $request)
    {
        $query = Kurir::where('status', 'AKTIF');

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('nohp', 'like', '%' . $request->search . '%');
            });
        }

        $kurir = $query->select('id_kurir', 'nama', 'email', 'nohp')
            ->orderBy('nama')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $kurir
        ]);
    }

    /**
     * Get pengiriman detail for AJAX
     */
    public function getPengirimanDetail($id_pengiriman)
    {
        $pengiriman = Pengiriman::with([
            'pengirim',
            'alamatTujuan',
            'alamatPenjemputan',
            'zonaPengiriman',
            'penugasanKurir.kurir'
        ])
            ->where('id_pengiriman', $id_pengiriman)
            ->first();

        if (!$pengiriman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengiriman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pengiriman
        ]);
    }

    /**
     * Get kurir detail for AJAX
     */
    public function getKurirDetail($id_kurir)
    {
        $kurir = Kurir::where('id_kurir', $id_kurir)
            ->where('status', 'AKTIF')
            ->first();

        if (!$kurir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurir tidak ditemukan'
            ], 404);
        }

        // Hitung jumlah penugasan aktif kurir
        $activeTasks = PenugasanKurir::where('id_kurir', $id_kurir)
            ->whereNotIn('status', ['SELESAI', 'DIBATALKAN'])
            ->count();

        $kurir->active_tasks = $activeTasks;

        return response()->json([
            'status' => 'success',
            'data' => $kurir
        ]);
    }

    /**
     * Get assignment history for specific pengiriman
     */
    public function getAssignmentHistory($id_pengiriman)
    {
        $history = PenugasanKurir::with('kurir')
            ->where('id_pengiriman', $id_pengiriman)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $history
        ]);
    }
}
