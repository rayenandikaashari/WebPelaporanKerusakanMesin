<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\SupervisorController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {
        Route::get('/dashboard', [OperatorController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/laporan', [OperatorController::class, 'laporan'])
            ->name('laporan');

        Route::get('/buat-laporan', [OperatorController::class, 'buatLaporan'])
            ->name('buat-laporan');

        Route::post('/buat-laporan', [OperatorController::class, 'simpanLaporan'])
            ->name('simpan-laporan');
    });

Route::middleware(['auth', 'role:teknisi'])
    ->prefix('teknisi')
    ->name('teknisi.')
    ->group(function () {
        Route::get('/dashboard', [TeknisiController::class, 'dashboard'])
            ->name('dashboard');

        Route::get(
            '/laporan/{id}/foto',
            [TeknisiController::class, 'foto']
        )->name('laporan.foto');

        Route::get('/laporan', [TeknisiController::class, 'laporan'])
            ->name('laporan.index');

        Route::get('/laporan/{id}', [TeknisiController::class, 'detailLaporan'])
            ->name('laporan.detail');

        Route::post('/laporan/{id}/status', [TeknisiController::class, 'updateStatus'])
            ->name('laporan.status');

        Route::get('/maintenance', [TeknisiController::class, 'maintenance'])
            ->name('maintenance.index');

        Route::post('/maintenance', [TeknisiController::class, 'simpanMaintenance'])
            ->name('maintenance.simpan');
    });

Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/mesin', [SupervisorController::class, 'mesin'])
            ->name('mesin');

        Route::get('/laporan', [SupervisorController::class, 'laporan'])
            ->name('laporan');

        Route::get('/maintenance', [SupervisorController::class, 'maintenance'])
            ->name('maintenance');

        Route::get('/riwayat/kerusakan', [SupervisorController::class, 'riwayatKerusakan'])
            ->name('riwayat.kerusakan');

        Route::get('/riwayat/perbaikan', [SupervisorController::class, 'riwayatPerbaikan'])
            ->name('riwayat.perbaikan');

        Route::get('/sparepart', [SupervisorController::class, 'sparepart'])
            ->name('sparepart');

        Route::get('/data/mesin', [SupervisorController::class, 'dataMesin'])
            ->name('data.mesin');

        Route::get('/data/mesin/tambah', [SupervisorController::class, 'createMesin'])
            ->name('mesin.create');

        Route::post('/data/mesin', [SupervisorController::class, 'storeMesin'])
            ->name('mesin.store');

        Route::get('/data/mesin/{kode}/edit', [SupervisorController::class, 'editMesin'])
            ->name('mesin.edit');

        Route::put('/data/mesin/{kode}', [SupervisorController::class, 'updateMesin'])
            ->name('mesin.update');

        Route::delete('/data/mesin/{kode}', [SupervisorController::class, 'destroyMesin'])
            ->name('mesin.destroy');

        Route::get('/data/sparepart', [SupervisorController::class, 'dataSparepart'])
            ->name('data.sparepart');

        Route::get('/data/sparepart/tambah', [SupervisorController::class, 'createSparepart'])
            ->name('sparepart.create');

        Route::post('/data/sparepart', [SupervisorController::class, 'storeSparepart'])
            ->name('sparepart.store');

        Route::get('/data/sparepart/{kode}/edit', [SupervisorController::class, 'editSparepart'])
            ->name('sparepart.edit');

        Route::put('/data/sparepart/{kode}', [SupervisorController::class, 'updateSparepart'])
            ->name('sparepart.update');

        Route::delete('/data/sparepart/{kode}', [SupervisorController::class, 'destroySparepart'])
            ->name('sparepart.destroy');

        Route::get('/data/pengguna', [SupervisorController::class, 'dataPengguna'])
            ->name('data.pengguna');

        Route::get('/data/pengguna/tambah', [SupervisorController::class, 'createPengguna'])
            ->name('pengguna.create');

        Route::post('/data/pengguna', [SupervisorController::class, 'storePengguna'])
            ->name('pengguna.store');

        Route::get('/data/pengguna/{id}/edit', [SupervisorController::class, 'editPengguna'])
            ->name('pengguna.edit');

        Route::put('/data/pengguna/{id}', [SupervisorController::class, 'updatePengguna'])
            ->name('pengguna.update');

        Route::delete('/data/pengguna/{id}', [SupervisorController::class, 'destroyPengguna'])
            ->name('pengguna.destroy');
    });
