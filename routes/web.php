<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PembayaranController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;

// Dokter Controllers
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;

// Pasien Controllers
use App\Http\Controllers\Pasien\PoliController;

// Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DokterExport;
use App\Exports\PasienExport;
use App\Exports\ObatExport;
use App\Exports\JadwalPeriksaExport;
use App\Exports\RiwayatPasienExport;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Shared Routes (Profile & Notifications)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Route Export (Wajib ditaruh SEBELUM resource)
    Route::get('/dokter/export', fn() => Excel::download(new DokterExport, 'data_dokter.xlsx'))->name('dokter.export');
    Route::get('/pasien/export', fn() => Excel::download(new PasienExport, 'data_pasien.xlsx'))->name('pasien.export');
    Route::get('/obat/export', fn() => Excel::download(new ObatExport, 'data_obat.xlsx'))->name('obat.export');

    // Resources
    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

    // Verifikasi Pembayaran Admin
    Route::get('/pembayaran', [PembayaranController::class, 'indexAdmin'])->name('admin.pembayaran.index');
    Route::patch('/pembayaran/{id}/konfirmasi', [PembayaranController::class, 'konfirmasiPembayaran'])->name('admin.pembayaran.konfirmasi');
});

/*
|--------------------------------------------------------------------------
| Dokter Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    // Export Jadwal Periksa & Riwayat Pasien
    Route::get('/jadwal-periksa/export', fn() => Excel::download(new JadwalPeriksaExport, 'jadwal_periksa.xlsx'))->name('dokter.jadwal-periksa.export');
    Route::get('/riwayat-pasien/export', fn() => Excel::download(new RiwayatPasienExport, 'riwayat_pasien.xlsx'))->name('dokter.riwayat-pasien.export');

    // Jadwal Periksa
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    // Periksa Pasien
    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');

    // Riwayat Pasien Dokter
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
});

/*
|--------------------------------------------------------------------------
| Pasien Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [PoliController::class, 'dashboard'])->name('pasien.dashboard');
    Route::get('/antrian-live', [PoliController::class, 'antrianLive'])->name('pasien.antrian.live');

    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');

    // Riwayat Pendaftaran Pasien
    Route::get('/riwayat', [PoliController::class, 'riwayat'])->name('pasien.riwayat');
    Route::get('/riwayat/{id}', [PoliController::class, 'detailRiwayat'])->name('pasien.riwayat.detail');

    // Pembayaran & Upload Bukti Pasien
    Route::get('/pembayaran', [PembayaranController::class, 'indexPasien'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{id}/upload', [PembayaranController::class, 'uploadBukti'])->name('pasien.pembayaran.upload');
});