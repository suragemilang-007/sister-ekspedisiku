<?php

use App\Http\Controllers\AlamatPenjemputanController;
use App\Http\Controllers\AlamatTujuanController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\pengaturanPenggunaController;
use App\Http\Controllers\PengirimanController;
use App\Models\AlamatTujuan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\penggunaController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\pengaturanAkunController;
use App\Http\Controllers\ZonaPengirimanController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\LayananController;

Route::get('/', function () {
    return view('welcome');
});

// route login & logut
Route::get('/login', [loginController::class, 'showLogin'])->name('login');
Route::post('/login', [loginController::class, 'login'])->name('login.post');
Route::get('/logout', [loginController::class, 'logout'])->name('logout');

// Route untuk admin
Route::prefix('admin')->middleware(['role:admin', 'auth.session'])->group(function () {
    // Dashboard utama untuk admin
    Route::get('/dashboard', [adminController::class, 'index'])->name('dashboard.admin');
    Route::get('/history', [adminController::class, 'history'])->name('dashboard.history');
    Route::get('/create-shipment', [penggunaController::class, 'createShipment'])->name('dashboard.create.shipment');
    Route::post('/create-shipment', [penggunaController::class, 'storeShipment'])->name('dashboard.store.shipment');
    Route::get('/edit', [pengaturanAkunController::class, 'edit'])->name('pengaturan.edit');
    Route::post('/update-info', [pengaturanAkunController::class, 'updateInfo'])->name('pengaturan.update.info');
    Route::post('/update-password', [pengaturanAkunController::class, 'updatePassword'])->name('pengaturan.update.password');

    // Route untuk mengelola Pengguna
    Route::get('/pengguna', [adminController::class, 'list'])->name('admin.pengguna.list');
    Route::post('/pengguna/store', [adminController::class, 'storeAdmin'])->name('admin.pengguna.store');
    Route::get('/pengguna/create', [adminController::class, 'create'])->name('admin.pengguna.create');
    Route::get('/pengguna/edit/{id}', [adminController::class, 'editAdmin'])->name('admin.pengguna.edit');
    Route::delete('pengguna/{id}', [adminController::class, 'deleteUser'])->name('admin.pengguna.delete');
    Route::post('/pengguna/update/{id}', [adminController::class, 'updateUserInfo'])->name('admin.pengguna.update');
    Route::post('/pengguna/update-password/{id}', [adminController::class, 'updateUserPassword'])->name('admin.pengguna.update.password');


    // Route untuk mengelola zona pengiriman
    Route::get('/zona', [ZonaPengirimanController::class, 'index'])->name('admin.zona.index');
    Route::get('/zona/create', [ZonaPengirimanController::class, 'create'])->name('admin.zona.create');
    Route::post('/zona/store', [ZonaPengirimanController::class, 'store'])->name('admin.zona.store');
    Route::get('/zona/{zonaPengiriman}', [ZonaPengirimanController::class, 'show'])->name('admin.zona.show');
    Route::get('/zona/edit/{id}', [ZonaPengirimanController::class, 'edit'])->name('admin.zona.edit');
    Route::post('/zona/update/{id}', [ZonaPengirimanController::class, 'update'])->name('admin.zona.update');
    Route::delete('/zona/{id}', [ZonaPengirimanController::class, 'deleteZona'])->name('admin.zona.delete');

    // Route untuk menglola layanan
    Route::get('/layanan', [LayananController::class, 'index'])->name('admin.layanan.index');
    Route::get('/layanan/create', [LayananController::class, 'create'])->name('admin.layanan.create');
    Route::post('/layanan/store', [LayananController::class, 'store'])->name('admin.layanan.store');
    Route::get('/layanan/edit/{id}', [LayananController::class, 'edit'])->name('admin.layanan.edit');
    Route::post('/layanan/update/{id}', [LayananController::class, 'update'])->name('admin.layanan.update');
    Route::delete('/layanan/{id}', [LayananController::class, 'delete'])->name('admin.layanan.delete');
});

// Route untuk pengirim
Route::prefix('dashboard/pengirim')->middleware(['role:pelanggan', 'auth.session'])->group(function () {
    // Dashboard utama untuk pengirim
    Route::get('/', [penggunaController::class, 'index'])->name('dashboard.pengirim');
    // Tracking
    Route::get('/tracking', [penggunaController::class, 'tracking'])->name('dashboard.tracking');
    Route::get('/tracking/{id}', [penggunaController::class, 'trackingDetail'])->name('dashboard.tracking.detail');
    // Riwayat pengiriman
    Route::get('/history', [penggunaController::class, 'history'])->name('dashboard.history');
    // Form pengiriman baru
    Route::get('/create-shipment', [penggunaController::class, 'createShipment'])->name('dashboard.create.shipment');
    Route::post('/create-shipment', [penggunaController::class, 'storeShipment'])->name('dashboard.store.shipment');
    // Feedback
    Route::get('/feedback', [penggunaController::class, 'feedback'])->name('dashboard.feedback');
    Route::post('/feedback/{id}', [penggunaController::class, 'submitFeedback'])->name('dashboard.submit.feedback');
    // Hitung biaya pengiriman (AJAX)
    Route::post('/calculate-cost', [penggunaController::class, 'calculateCost'])->name('dashboard.calculate.cost');
    Route::get("/dashboard/pengirim/detail/{id}", [penggunaController::class, 'showDetail'])->name('dashboard.pengirim.detail');
    Route::get('/pengguna/edit', [pengaturanPenggunaController::class, 'edit'])->name('pengaturan.edit');
    Route::get('/alamat-tujuan', [AlamatTujuanController::class, 'index'])->name('alamattujuan.index');
});



// Untuk pelanggan (pelanggan)
Route::middleware(['role:pelanggan', 'auth.session'])->group(function () {
    Route::get('/pengguna/edit', [pengaturanPenggunaController::class, 'edit'])->name('pengaturan.edit');
    Route::post('/pengguna/update-info', [pengaturanPenggunaController::class, 'updateInfo'])->name('pengaturan.update.info');
    Route::post('/pengguna/update-password', [pengaturanPenggunaController::class, 'updatePassword'])->name('pengaturan.update.password');
    Route::get('/detail/{id}', [penggunaController::class, 'showDetail'])->name('pengiriman.detail');
});

// Untuk admin
Route::middleware(['role:admin', 'auth.session'])->group(callback: function () {
    Route::post('/admin/update-info', [pengaturanPenggunaController::class, 'updateInfo'])->name('admin.pengaturan.update.info');
    Route::post('/admin/update-password', [pengaturanPenggunaController::class, 'updatePassword'])->name('admin.pengaturan.update.password');
    Route::get('/admin/detail/{id}', [penggunaController::class, 'showDetail'])->name('admin.pengiriman.detail');
});

// Route untuk kurir
Route::prefix('kurir')->middleware(['role:kurir', 'auth.session'])->group(function () {
    // Dashboard kurir
    Route::get('/dashboard', [KurirController::class, 'dashboard'])->name('kurir.dashboard');
    // Detail tugas kurir
    Route::get('/detail/{id_penugasan}', [KurirController::class, 'detail'])->name('kurir.detail');
    // Update status pengiriman
    Route::post('/update-status', [KurirController::class, 'updateStatus'])->name('kurir.update.status');
    // API untuk data dashboard (opsional untuk refresh data)
    Route::get('/dashboard-data', [KurirController::class, 'dashboardData'])->name('kurir.dashboard.data');
});

// Group routes untuk feedback (memerlukan autentikasi)
Route::middleware(['role:pelanggan', 'auth.session'])->group(function () {
    // Halaman utama feedback
    Route::get('dashboard/pengirim/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    // Form untuk memberikan feedback
    Route::get('/feedback/create/{id_pengiriman}', [FeedbackController::class, 'create'])->name('pengguna.createFeedback');
    // Simpan feedback baru
    Route::post('/feedback/store', [FeedbackController::class, 'store']);
    // Tampilkan detail feedback
    Route::get('/feedback/{id_pengiriman}', [FeedbackController::class, 'show'])->name('feedback.show');
    // Form edit feedback (opsional)
    Route::get('/feedback/{id_pengiriman}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    // Update feedback (opsional)
    Route::put('/feedback/{id_pengiriman}', [FeedbackController::class, 'update'])->name('feedback.update');
    // Hapus feedback (opsional)
    Route::delete('/feedback/{id_pengiriman}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    // API statistik feedback (opsional)
    Route::get('/feedback/api/statistics', [FeedbackController::class, 'statistics'])->name('feedback.statistics');
});

Route::middleware(['role:pelanggan', 'auth.session'])->group(function () {
    Route::resource('alamat-tujuan', AlamatTujuanController::class);
    Route::get('api/alamat-tujuan', [AlamatTujuanController::class, 'getAlamatTujuan']);
    Route::get('api/alamat-tujuan/{id}', [AlamatTujuanController::class, 'getAlamatTujuanDetail']);
    Route::post('/alamat-tujuan/store', [AlamatTujuanController::class, 'store'])->name('alamat-tujuan.store');
    Route::get('/alamat-tujuan', [AlamatTujuanController::class, 'index'])->name('alamat-tujuan.index');
    Route::get('/alamat-tujuan/edit/{id}', [AlamatTujuanController::class, 'edit'])->name('alamat-tujuan.edit');
    Route::post('/alamat-tujuan/update/{id}', [AlamatTujuanController::class, 'update'])->name('alamat-tujuan.update');
    Route::delete('/alamat-tujuan/delete/{id}', [AlamatTujuanController::class, 'destroy'])->name('alamat-tujuan.destroy');
});

Route::middleware(['role:pelanggan', 'auth.session'])->group(function () {
    // Pengiriman routes
    Route::get('/dashboard/pengirim/kirim', [PengirimanController::class, 'create'])->name('pengiriman.create');
    Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
    Route::get('/pengiriman/{id}', [PengirimanController::class, 'show'])->name('pengiriman.show');
    Route::post('/pengiriman/{id}/cancel', [PengirimanController::class, 'cancel'])->name('pengiriman.cancel');

    // AJAX routes
    Route::get('/api/zona-pengiriman', [PengirimanController::class, 'getZonaPengiriman']);
    Route::get('/api/kecamatan-tujuan', [PengirimanController::class, 'getKecamatanTujuan']);
    Route::get('/api/layanan-paket/{id}', [PengirimanController::class, 'getLayananPaket']);

    // Tracking
    Route::get('/track/{resi}', [PengirimanController::class, 'track'])->name('pengiriman.track');

    // Alamat Penjemputan routes
    Route::resource('alamat-penjemputan', AlamatPenjemputanController::class);
    Route::get('/api/alamat-penjemputan', [AlamatPenjemputanController::class, 'getAlamatPenjemputan']);
    Route::get('/api/alamat-penjemputan/{id}', [AlamatPenjemputanController::class, 'getAlamatPenjemputanDetail']);
});
