<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LayananPaket;


class LayananController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil parameter pencarian dari request
        $search = $request->input('search');

        // Memulai query untuk mendapatkan data LayananPaket
        $query = LayananPaket::query();

        // Menerapkan kondisi pencarian jika ada input 'search'
        if ($search) {
            $query->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        // Mengambil data dengan paginasi
        $layananPaket = $query->paginate(10);

        // Menggunakan withQueryString untuk mempertahankan semua parameter (pencarian) di URL
        $layananPaket->withQueryString();
        // Mengembalikan view dengan data layananPaket
        // Pastikan untuk mengirimkan data yang diperlukan ke view
        // Misalnya, jika view membutuhkan data layananPaket, kita bisa mengirimkannya
        // Jika ada data lain yang perlu dikirim, tambahkan ke compact
        // Contoh: compact('layananPaket', 'dataLain')
        return view('admin.layanan.index', compact('layananPaket'));
    }

    public function create()
    {
        // Menampilkan form untuk membuat layanan baru
        return view('admin.layanan.create');
    }
}
