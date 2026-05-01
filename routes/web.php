<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\TenagaMedisController;
use App\Http\Controllers\Admin\PenyakitController as AdminPenyakit;
use App\Http\Controllers\Admin\ObatController as AdminObat;

use App\Http\Controllers\Medis\DashboardController as MedisDashboard;
use App\Http\Controllers\Medis\PresensiController;
use App\Http\Controllers\Medis\LayananController;
use App\Http\Controllers\Medis\KonsultasiController;
use App\Http\Controllers\Medis\ObatController as MedisObat;
use App\Http\Controllers\Medis\PenyakitController as MedisPenyakit;
use App\Http\Controllers\Medis\PermintaanObatController;
use App\Http\Controllers\Medis\KwitansiController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('tenaga-medis', TenagaMedisController::class);
    Route::resource('penyakit', AdminPenyakit::class);
    Route::resource('obat', AdminObat::class);
});

Route::middleware('auth')->prefix('medis')->name('medis.')->group(function () {
    Route::get('/dashboard', [MedisDashboard::class, 'index'])->name('dashboard');
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    
    Route::get('/layanan/{jenis}', [LayananController::class, 'index'])->name('layanan.index');
    Route::post('/layanan/{jenis}', [LayananController::class, 'store'])->name('layanan.store');
    
    Route::get('/konsultasi/scan', [KonsultasiController::class, 'index'])->name('konsultasi.scan');
    Route::post('/konsultasi/scan', [KonsultasiController::class, 'processScan'])->name('konsultasi.process');
    
    Route::get('/permintaan-obat', [PermintaanObatController::class, 'index'])->name('permintaan-obat.index');
    Route::get('/permintaan-obat/scan', [PermintaanObatController::class, 'scan'])->name('permintaan-obat.scan');
    Route::post('/permintaan-obat/scan', [PermintaanObatController::class, 'processScan'])->name('permintaan-obat.process');
    Route::get('/permintaan-obat/create', [PermintaanObatController::class, 'create'])->name('permintaan-obat.create');
    Route::post('/permintaan-obat', [PermintaanObatController::class, 'store'])->name('permintaan-obat.store');
    
    Route::get('/kwitansi', [KwitansiController::class, 'index'])->name('kwitansi.index');
    Route::get('/kwitansi/scan', [KwitansiController::class, 'scan'])->name('kwitansi.scan');
    Route::post('/kwitansi/scan', [KwitansiController::class, 'processScan'])->name('kwitansi.process');
    Route::get('/kwitansi/create', [KwitansiController::class, 'create'])->name('kwitansi.create');
    Route::post('/kwitansi', [KwitansiController::class, 'store'])->name('kwitansi.store');

    Route::resource('obat', MedisObat::class);
    Route::resource('penyakit', MedisPenyakit::class);
});