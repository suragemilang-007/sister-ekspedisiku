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
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AdminPenugasanKurirController;

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
    Route::delete('pengguna/{uid}', [adminController::class, 'deleteUser'])->name('admin.pengguna.delete');
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
    Route::post('/layanan/store', [LayananController::class, 'storeLayanan'])->name('admin.layanan.store');
    Route::get('/layanan/edit/{id}', [LayananController::class, 'edit'])->name('admin.layanan.edit');
    Route::post('/layanan/update/{id}', [LayananController::class, 'update'])->name('admin.layanan.update');
    Route::delete('/layanan/{id}', [LayananController::class, 'deleteLayanan'])->name('admin.layanan.delete');

    // Route untuk mengelola pengiriman 
    Route::get('/pesanan/baru', [PengirimanController::class, 'pesananBaru'])->name('admin.pesanan.baru.index');
    Route::post('/pesanan/update-status/{id}', [PengirimanController::class, 'dibatalkan'])->name('admin.pesanan.update.status');
    Route::get('/pesanan/list', [PengirimanController::class, 'semuaPesanan'])->name('admin.pesanan.list');
    Route::post('/pesanan/assign-kurir', [PengirimanController::class, 'assignKurir'])->name('admin.assign.kurir');


    // Route Penugasan
    Route::resource('penugasan-kurir', AdminPenugasanKurirController::class);
    Route::post('penugasan-kurir/{id}/cancel', [AdminPenugasanKurirController::class, 'cancel'])->name('admin.penugasan-kurir.cancel');

    // AJAX Routes
    Route::get('api/pengiriman-list', [AdminPenugasanKurirController::class, 'getPengirimanList']);
    Route::get('api/kurir-list', [AdminPenugasanKurirController::class, 'getKurirList']);
    Route::get('api/pengiriman-detail/{id}', [AdminPenugasanKurirController::class, 'getPengirimanDetail']);
    Route::get('api/kurir-detail/{id}', [AdminPenugasanKurirController::class, 'getKurirDetail']);
    Route::get('api/assignment-history/{id}', [AdminPenugasanKurirController::class, 'getAssignmentHistory']);

    // Route untuk Manajemen Kurir
    Route::get('/kurir', [KurirController::class, 'listKurir'])->name('admin.kurir.index');
    Route::get('/kurir/create', [KurirController::class,'createKurir'])->name('admin.kurir.create');
    Route::post('/kurir/store', [KurirController::class,'storeKurir'])->name('admin.kurir.store');
    Route::get('/kurir/edit/{id}', [KurirController::class,'edit'])->name('admin.kurir.edit');
    Route::post('/kurir/update-info', [KurirController::class,'profileUpdate'])->name('admin.kurir.update.info');
    Route::post('/kurir/update-password', [KurirController::class,'passwordChange'])->name('admin.kurir.update.password');
    Route::delete('/kurir/{id}', [KurirController::class,'deleteKurir'])->name('admin.kurir.delete');

    // Route untuk update status penugasan kurir oleh kurir
    Route::post('/kurir/penugasan/update-status', [App\Http\Controllers\AdminPenugasanKurirController::class, 'updateStatusKurir'])->name('kurir.penugasan.updateStatus');

});

// Route untuk pengirim
Route::prefix('dashboard/pengirim')->middleware(['role:pelanggan', 'auth.session'])->group(function () {
    // Dashboard utama untuk pengirim
    Route::get('/', [penggunaController::class, 'index'])->name('dashboard.pengirim');
    // Riwayat pengiriman
    Route::get('/history', [penggunaController::class, 'history'])->name('dashboard.history.pengiriman');
    Route::get('/feedbackCount', [penggunaController::class, 'feedbackSidebar'])->name('dashboard.history.feedbackCount');

    Route::get("/dashboard/pengirim/detail/{id}", [penggunaController::class, 'showDetail'])->name('dashboard.pengirim.detail');
    Route::get('/pengguna/edit', [pengaturanPenggunaController::class, 'edit'])->name('pengaturan.edit');
    Route::get('/alamat-tujuan', [AlamatTujuanController::class, 'index'])->name('alamattujuan.index');
});

// Route untuk redirect kurir yang mencoba akses dashboard pengirim
// Route::get('/dashboard/pengirim', function () {
//     if (Session::get('user_role') === 'kurir') {
//         return redirect('/kurir/dashboard')->with('warning', 'Anda diarahkan ke dashboard kurir.');
//     }
//     return redirect('/login');
// })->middleware('auth.session');

// // Route untuk redirect kurir yang mencoba akses dashboard admin
// Route::get('/admin/dashboard', function () {
//     if (Session::get('user_role') === 'kurir') {
//         return redirect('/kurir/dashboard')->with('warning', 'Anda diarahkan ke dashboard kurir.');
//     }
//     return redirect('/login');
// })->middleware('auth.session');

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
    // Tugas pengiriman
    Route::get('/tugas', [KurirController::class, 'tugas'])->name('kurir.tugas');
    // Riwayat pengiriman
    Route::get('/riwayat', [KurirController::class, 'riwayat'])->name('kurir.riwayat');
    // Feedback
    Route::get('/feedback', [KurirController::class, 'feedback'])->name('kurir.feedback');
    // Pengaturan
    Route::get('/pengaturan', [KurirController::class, 'pengaturan'])->name('kurir.pengaturan');
    // Update informasi kurir
    Route::post('/update-info', [KurirController::class, 'updateInfo'])->name('kurir.update.info');
    // Update password kurir
    Route::post('/update-password', [KurirController::class, 'updatePassword'])->name('kurir.update.password');
    // Detail tugas kurir
    Route::get('/detail/{id_penugasan}', [KurirController::class, 'detail'])->name('kurir.detail');
    // Update status pengiriman
    Route::get('/update/{id_penugasan}', [KurirController::class, 'showUpdateForm'])->name('kurir.update.form');
    Route::post('/update-status', [KurirController::class, 'updateStatus'])->name('kurir.update.status');
    // API untuk data dashboard (opsional untuk refresh data)
    Route::get('/dashboard-data', [KurirController::class, 'dashboardData'])->name('kurir.dashboard.data');
});

// Group routes untuk feedback 
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

});

Route::middleware(['role:pelanggan', 'auth.session'])->group(function () {
    Route::resource('alamat-penjemputan', AlamatPenjemputanController::class);
    Route::get('api/alamat-penjemputan', [AlamatPenjemputanController::class, 'getAlamatPenjemputan']);
    Route::get('api/alamat-penjemputan/{id}', [AlamatPenjemputanController::class, 'getAlamatPenjemputanDetail']);
    Route::post('/alamat-penjemputan/store', [AlamatPenjemputanController::class, 'store'])->name('alamat-penjemputan.store');
    Route::get('/alamat-penjemputan', [AlamatPenjemputanController::class, 'index'])->name('alamat-penjemputan.index');
    Route::get('/alamat-penjemputan/edit/{id}', [AlamatPenjemputanController::class, 'edit'])->name('alamat-penjemputan.edit');
    Route::post('/alamat-penjemputan/update/{id}', [AlamatPenjemputanController::class, 'update'])->name('alamat-penjemputan.update');
    Route::delete('/alamat-penjemputan/delete/{id}', [AlamatPenjemputanController::class, 'destroy'])->name('alamat-penjemputan.destroy');
});



//buat testing
Route::prefix('dashboard/pengiriman')->group(function () {
    Route::get('/edit-status/{id}', [PengirimanController::class, 'editStatus'])->name('pengiriman.editStatus');
    Route::post('/update-status', [PengirimanController::class, 'updateStatus'])->name('pengiriman.updateStatus');
});

// Route untuk update status pengiriman oleh kurir
Route::post('/kurir/pengiriman/update-status', [App\Http\Controllers\PengirimanController::class, 'updateStatus'])->name('kurir.pengiriman.updateStatus');
// Route untuk konfirmasi diterima oleh kurir (update tanggal_sampai, catatan_opsional, foto_bukti_sampai)
Route::post('/kurir/pengiriman/konfirmasi-diterima', [App\Http\Controllers\KurirController::class, 'konfirmasiDiterima'])->name('kurir.pengiriman.konfirmasiDiterima');