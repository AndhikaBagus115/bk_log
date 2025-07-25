<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BKLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bk', [BKLogController::class, 'index']) ->name('bk.index');
Route::post('/bk', [BKLogController::class, 'store']);
Route::get('/bk/export-excel', [BKLogController::class, 'exportExcel']) ->name('bk.export.excel');
Route::get('/bk/export-pdf', [BKLogController::class, 'exportPDF'])->name('bk.export.pdf');

