<?php

use App\Http\Controllers\loginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\penggunaController;

Route::get('/', function () {
    return view('welcome');
});

// route login & logut
Route::get('/login', [loginController::class, 'showLogin'])->name('login');
Route::post('/login', [loginController::class, 'login'])->name('login.post');
Route::get('/logout', [loginController::class, 'logout'])->name('logout');



// Route untuk pengirim
Route::prefix('dashboard')->middleware(['role:pelanggan', 'auth.session'])->group(function () {
    // Dashboard utama untuk pengirim
    Route::get('/pengirim', [penggunaController::class, 'index'])->name('dashboard.pengirim');
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
});