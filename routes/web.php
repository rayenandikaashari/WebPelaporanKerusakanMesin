<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OperatorController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\SupervisorController;


/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()->route('operator.login');

});


/*
|--------------------------------------------------------------------------
| OPERATOR
|--------------------------------------------------------------------------
*/

// Login
Route::get('/operator/login', [OperatorController::class, 'login'])
    ->name('operator.login');

Route::post('/operator/login', [OperatorController::class, 'authenticate'])
    ->name('operator.authenticate');


// Dashboard
Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])
    ->name('operator.dashboard');


// Laporan Kerusakan
Route::get('/operator/laporan', [OperatorController::class, 'laporan'])
    ->name('operator.laporan');

Route::get('/operator/laporan/buat', [OperatorController::class, 'buatLaporan'])
    ->name('operator.laporan.buat');

Route::post('/operator/laporan', [OperatorController::class, 'simpanLaporan'])
    ->name('operator.laporan.simpan');


// Logout
Route::post('/operator/logout', [OperatorController::class, 'logout'])
    ->name('operator.logout');


/*
|--------------------------------------------------------------------------
| TEKNISI
|--------------------------------------------------------------------------
*/

// Login
Route::get('/teknisi/login', [TeknisiController::class, 'login'])
    ->name('teknisi.login');

Route::post('/teknisi/login', [TeknisiController::class, 'authenticate'])
    ->name('teknisi.authenticate');


// Dashboard
Route::get('/teknisi/dashboard', [TeknisiController::class, 'dashboard'])
    ->name('teknisi.dashboard');


/*
|--------------------------------------------------------------------------
| LAPORAN KERUSAKAN TEKNISI
|--------------------------------------------------------------------------
*/

Route::get('/teknisi/laporan', [TeknisiController::class, 'laporan'])
    ->name('teknisi.laporan');

Route::get('/teknisi/laporan/{id}', [TeknisiController::class, 'detailLaporan'])
    ->name('teknisi.laporan.detail');


/*
|--------------------------------------------------------------------------
| UPDATE PERBAIKAN
|--------------------------------------------------------------------------
*/

Route::post('/teknisi/laporan/{id}/status', [TeknisiController::class, 'updateStatus'])
    ->name('teknisi.laporan.status');


/*
|--------------------------------------------------------------------------
| MAINTENANCE
|--------------------------------------------------------------------------
*/

Route::get('/teknisi/maintenance', [TeknisiController::class, 'maintenance'])
    ->name('teknisi.maintenance');

Route::post('/teknisi/maintenance', [TeknisiController::class, 'simpanMaintenance'])
    ->name('teknisi.maintenance.simpan');


/*
|--------------------------------------------------------------------------
| LOGOUT TEKNISI
|--------------------------------------------------------------------------
*/

Route::post('/teknisi/logout', [TeknisiController::class, 'logout'])
    ->name('teknisi.logout');


/*
|--------------------------------------------------------------------------
| SUPERVISOR
|--------------------------------------------------------------------------
*/

Route::prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Route::get('/login', [SupervisorController::class, 'login'])
            ->name('login');

        Route::post('/login', [SupervisorController::class, 'authenticate'])
            ->name('login.authenticate');


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [SupervisorController::class, 'dashboard'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | STATUS MESIN
        |--------------------------------------------------------------------------
        */

        Route::get('/mesin', [SupervisorController::class, 'mesin'])
            ->name('mesin');


        /*
        |--------------------------------------------------------------------------
        | LAPORAN KERUSAKAN
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan', [SupervisorController::class, 'laporan'])
            ->name('laporan');


        /*
        |--------------------------------------------------------------------------
        | LAPORAN MAINTENANCE
        |--------------------------------------------------------------------------
        */

        Route::get('/maintenance', [SupervisorController::class, 'maintenance'])
            ->name('maintenance');


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT
        |--------------------------------------------------------------------------
        */

        Route::get('/riwayat/kerusakan', [SupervisorController::class, 'riwayatKerusakan'])
            ->name('riwayat.kerusakan');

        Route::get('/riwayat/perbaikan', [SupervisorController::class, 'riwayatPerbaikan'])
            ->name('riwayat.perbaikan');


        /*
        |--------------------------------------------------------------------------
        | SPAREPART
        |--------------------------------------------------------------------------
        */

        Route::get('/sparepart', [SupervisorController::class, 'sparepart'])
            ->name('sparepart');


        /*
        |--------------------------------------------------------------------------
        | DATA MASTER
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | DATA MESIN
        |--------------------------------------------------------------------------
        */

        // Menampilkan daftar mesin
        Route::get('/data/mesin', [SupervisorController::class, 'dataMesin'])
            ->name('data.mesin');

        // Form tambah mesin
        Route::get('/data/mesin/tambah', [SupervisorController::class, 'createMesin'])
            ->name('mesin.create');

        // Menyimpan mesin baru
        Route::post('/data/mesin', [SupervisorController::class, 'storeMesin'])
            ->name('mesin.store');

        // Form edit mesin
        Route::get('/data/mesin/{kode}/edit', [SupervisorController::class, 'editMesin'])
            ->name('mesin.edit');

        // Update mesin
        Route::put('/data/mesin/{kode}', [SupervisorController::class, 'updateMesin'])
            ->name('mesin.update');

        // Hapus mesin
        Route::delete('/data/mesin/{kode}', [SupervisorController::class, 'destroyMesin'])
            ->name('mesin.destroy');


        /*
        |--------------------------------------------------------------------------
        | DATA SPAREPART
        |--------------------------------------------------------------------------
        */

        // Menampilkan daftar sparepart
        Route::get('/data/sparepart', [SupervisorController::class, 'dataSparepart'])
            ->name('data.sparepart');

        // Form tambah sparepart
        Route::get('/data/sparepart/tambah', [SupervisorController::class, 'createSparepart'])
            ->name('sparepart.create');

        // Menyimpan sparepart baru
        Route::post('/data/sparepart', [SupervisorController::class, 'storeSparepart'])
            ->name('sparepart.store');

        // Form edit sparepart
        Route::get('/data/sparepart/{kode}/edit', [SupervisorController::class, 'editSparepart'])
            ->name('sparepart.edit');

        // Update sparepart
        Route::put('/data/sparepart/{kode}', [SupervisorController::class, 'updateSparepart'])
            ->name('sparepart.update');

        // Hapus sparepart
        Route::delete('/data/sparepart/{kode}', [SupervisorController::class, 'destroySparepart'])
            ->name('sparepart.destroy');


        /*
        |--------------------------------------------------------------------------
        | DATA PENGGUNA
        |--------------------------------------------------------------------------
        */

        Route::get('/data/pengguna', [SupervisorController::class, 'dataPengguna'])
            ->name('data.pengguna');

        // Form tambah pengguna
        Route::get('/data/pengguna/tambah', [SupervisorController::class, 'createPengguna'])
            ->name('pengguna.create');

        // Simpan pengguna baru
        Route::post('/data/pengguna', [SupervisorController::class, 'storePengguna'])
            ->name('pengguna.store');

        // Form edit pengguna
        Route::get('/data/pengguna/{id}/edit', [SupervisorController::class, 'editPengguna'])
            ->name('pengguna.edit');

        // Update pengguna
        Route::put('/data/pengguna/{id}', [SupervisorController::class, 'updatePengguna'])
            ->name('pengguna.update');

        // Hapus pengguna
        Route::delete('/data/pengguna/{id}', [SupervisorController::class, 'destroyPengguna'])
            ->name('pengguna.destroy');

        /*
        |--------------------------------------------------------------------------
        | LOGOUT SUPERVISOR
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', [SupervisorController::class, 'logout'])
            ->name('logout');

    });