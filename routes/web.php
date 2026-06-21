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
use App\Http\Controllers\Admin\ReportController;

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

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/presensi-tenaga-medis', [ReportController::class, 'presensiTenagaMedis'])->name('presensi-tenaga-medis');
        Route::get('/presensi-tenaga-medis/export', [ReportController::class, 'exportPresensiTenagaMedis'])->name('presensi-tenaga-medis.export');

        Route::get('/istirahat-sakit', [ReportController::class, 'istirahatSakit'])->name('istirahat-sakit');
        Route::get('/istirahat-sakit/export', [ReportController::class, 'exportIstirahatSakit'])->name('istirahat-sakit.export');

        Route::get('/istirahat-hamil', [ReportController::class, 'istirahatHamil'])->name('istirahat-hamil');
        Route::get('/istirahat-hamil/export', [ReportController::class, 'exportIstirahatHamil'])->name('istirahat-hamil.export');

        Route::get('/laktasi', [ReportController::class, 'laktasi'])->name('laktasi');
        Route::get('/laktasi/export', [ReportController::class, 'exportLaktasi'])->name('laktasi.export');

        Route::get('/konsultasi', [ReportController::class, 'konsultasi'])->name('konsultasi');
        Route::get('/konsultasi/export', [ReportController::class, 'exportKonsultasi'])->name('konsultasi.export');

        Route::get('/permintaan-obat', [ReportController::class, 'permintaanObat'])->name('permintaan-obat');
        Route::get('/permintaan-obat/export', [ReportController::class, 'exportPermintaanObat'])->name('permintaan-obat.export');

        Route::get('/kwitansi', [ReportController::class, 'kwitansi'])->name('kwitansi');
        Route::get('/kwitansi/export', [ReportController::class, 'exportKwitansi'])->name('kwitansi.export');

        Route::get('/kunjungan', [ReportController::class, 'kunjungan'])->name('kunjungan');
        Route::get('/kunjungan/export', [ReportController::class, 'exportKunjungan'])->name('kunjungan.export');
    });
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