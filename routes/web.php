<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\pengaturanPenggunaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\penggunaController;
use App\Http\Controllers\adminController;

Route::get('/', function () {
    return view('welcome');
});

// route login & logut
Route::get('/login', [loginController::class, 'showLogin'])->name('login');
Route::post('/login', [loginController::class, 'login'])->name('login.post');
Route::get('/logout', [loginController::class, 'logout'])->name('logout');

// Route untuk admin
Route::prefix('dashboard/admin')->middleware(['role:admin', 'auth.session'])->group(function () {
    // Dashboard utama untuk pengirim
    Route::get('/', [adminController::class, 'index'])->name('dashboard.admin');
    // Tracking
    Route::get('/tracking', [penggunaController::class, 'tracking'])->name('dashboard.tracking');
    Route::get('/tracking/{id}', [penggunaController::class, 'trackingDetail'])->name('dashboard.tracking.detail');
    // Riwayat pengiriman
    Route::get('/history', [adminController::class, 'history'])->name('dashboard.history');
    // Form pengiriman baru
    Route::get('/create-shipment', [penggunaController::class, 'createShipment'])->name('dashboard.create.shipment');
    Route::post('/create-shipment', [penggunaController::class, 'storeShipment'])->name('dashboard.store.shipment');
    // Feedback
    Route::get('/feedback', [penggunaController::class, 'feedback'])->name('dashboard.feedback');
    Route::post('/feedback/{id}', [penggunaController::class, 'submitFeedback'])->name('dashboard.submit.feedback');
    // Hitung biaya pengiriman (AJAX)
    Route::post('/calculate-cost', [penggunaController::class, 'calculateCost'])->name('dashboard.calculate.cost');

    Route::get('/pengguna/edit', [pengaturanPenggunaController::class, 'edit'])->name('pengaturan.edit');
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

    Route::get('/pengguna/edit', [pengaturanPenggunaController::class, 'edit'])->name('pengaturan.edit');
});



Route::middleware(['role:pelanggan', 'auth.session'])->group(function () {
    Route::get('/pengguna/edit', [pengaturanPenggunaController::class, 'edit'])->name('pengaturan.edit');
    Route::post('/pengguna/update-info', [pengaturanPenggunaController::class, 'updateInfo'])->name('pengaturan.update.info');
    Route::post('/pengguna/update-password', [pengaturanPenggunaController::class, 'updatePassword'])->name('pengaturan.update.password');
    Route::get('/detail/{id}', [penggunaController::class, 'showDetail'])->name('pengiriman.detail');
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