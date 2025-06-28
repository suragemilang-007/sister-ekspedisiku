<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LayananPaket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = LayananPaket::query();

        // Filter by search term
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_layanan', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Sort by selected criteria
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($request->sort_by, $sortOrder);
        } else {
            $query->orderBy('id_layanan', 'asc'); // Default sort
        }

        // Paginate results
        $layananPaket = $query->paginate(10);

        return view('admin.layanan.index', compact('layananPaket'));
    }

    public function create()
    {
        // Menampilkan form untuk membuat layanan baru
        return view('admin.layanan.create');
    }

    public function storeLayanan(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'berat_minimal' => 'required|numeric|min:0',
            'berat_maksimal' => 'required|numeric|min:0',
            'biaya' => 'required|numeric|min:0',
        ]);

        try {
            $dataToProducer = [
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
                'min_berat' => $request->berat_minimal,
                'max_berat' => $request->berat_maksimal,
                'harga_dasar' => $request->biaya,
                'action_type' => 'add_layanan',
            ];

            $response = Http::timeout(5)->post('http://localhost:3001/layanan/add', $dataToProducer);

            if ($response->successful()) {
                Log::info('Layanan Paket berhasil dikirim ke Kafka');
                return response()->json(['message' => 'Layanan Paket berhasil dibuat.'], 200);
            } else {
                Log::error('Gagal mengirim layanan Paket ke Kafka: ' . $response->body());
                return response()->json(['message' => 'Gagal membuat layanan Paket.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error saat membuat layanan Paket: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat membuat layanan Paket.'], 500);
        }
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit layanan
        $layananPaket = LayananPaket::findOrFail($id);
        return view('admin.layanan.edit', compact('layananPaket'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'min_berat' => 'required|numeric|min:0',
            'max_berat' => 'required|numeric|min:0',
            'harga_dasar' => 'required|numeric|min:0',
        ]);

        try {
            $dataToProducer = [
                'id_layanan' => $request->id_layanan,
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
                'min_berat' => $request->min_berat,
                'max_berat' => $request->max_berat,
                'harga_dasar' => $request->harga_dasar,
                'action_type' => 'update_layanan',
            ];

            $response = Http::timeout(5)->post('http://localhost:3001/layanan/update', $dataToProducer);

            if ($response->successful()) {
                Log::info('Layanan Paket berhasil diperbarui dan dikirim ke Kafka');
                return response()->json(['message' => 'Layanan Paket berhasil diperbarui.'], 200);
            } else {
                Log::error('Gagal mengirim layanan Paket ke Kafka: ' . $response->body());
                return response()->json(['message' => 'Gagal memperbarui layanan Paket.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui layanan Paket: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui layanan Paket.'], 500);
        }
    }

    public function deleteLayanan($id)
    {
        // Validasi input
        $layananPaket = LayananPaket::findOrFail($id);

        try {
            $dataToProducer = [
                'id_layanan' => $layananPaket->id_layanan,
                'action_type' => 'delete_layanan',
            ];

            $response = Http::timeout(5)->post('http://localhost:3001/layanan/delete', $dataToProducer);

            if ($response->successful()) {
                Log::info('Permintaan penghapusan layanan Paket berhasil dikirim ke Kafka');
                return response()->json(['message' => 'Permintaan penghapusan layanan Paket telah dikirim. Layanan Paket akan segera dihapus.'], 200);
            } else {
                Log::error('Gagal mengirim permintaan penghapusan layanan Paket ke Kafka: ' . $response->body());
                return response()->json(['message' => 'Gagal menghapus layanan Paket.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error saat menghapus layanan Paket: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus layanan Paket.'], 500);
        }
    }

}
