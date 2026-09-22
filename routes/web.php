<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\TeknisiController;

Route::get('/', function () {
    return redirect()->route('operator.login');
});


// =============================
// OPERATOR
// =============================

Route::get('/operator/login', [OperatorController::class, 'login'])
    ->name('operator.login');

Route::post('/operator/login', [OperatorController::class, 'authenticate'])
    ->name('operator.authenticate');

Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])
    ->name('operator.dashboard');

Route::get('/operator/laporan', [OperatorController::class, 'laporan'])
    ->name('operator.laporan');

Route::get('/operator/laporan/buat', [OperatorController::class, 'buatLaporan'])
    ->name('operator.laporan.buat');

Route::post('/operator/laporan', [OperatorController::class, 'simpanLaporan'])
    ->name('operator.laporan.simpan');

Route::post('/operator/logout', [OperatorController::class, 'logout'])
    ->name('operator.logout');

/*
|--------------------------------------------------------------------------
| TEKNISI
|--------------------------------------------------------------------------
*/

Route::get('/teknisi/login', [TeknisiController::class, 'login'])
    ->name('teknisi.login');

Route::post('/teknisi/login', [TeknisiController::class, 'authenticate'])
    ->name('teknisi.authenticate');

Route::get('/teknisi/dashboard', [TeknisiController::class, 'dashboard'])
    ->name('teknisi.dashboard');


/*
|--------------------------------------------------------------------------
| LAPORAN KERUSAKAN
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

Route::get(
    '/teknisi/maintenance',
    [TeknisiController::class, 'maintenance']
)->name('teknisi.maintenance');


Route::post(
    '/teknisi/maintenance',
    [TeknisiController::class, 'simpanMaintenance']
)->name('teknisi.maintenance.simpan');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/teknisi/logout', [TeknisiController::class, 'logout'])
    ->name('teknisi.logout');