<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;

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