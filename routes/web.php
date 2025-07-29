<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BKLogController;
use App\Http\Controllers\ClientWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [ClientWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ClientWebController::class, 'login']);
Route::post('/logout', [ClientWebController::class, 'logout'])->name('logout');

Route::get('/bk', [BKLogController::class, 'index']) ->name('bk.index');
Route::post('/bk', [BKLogController::class, 'store']);
Route::get('/bk/export-excel', [BKLogController::class, 'exportExcel']) ->name('bk.export.excel');
Route::get('/bk/export-pdf', [BKLogController::class, 'exportPDF'])->name('bk.export.pdf');




