<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BKLogApiController;
use App\Http\Middleware\ClientMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(ClientMiddleware::class)->group(function () {
    Route::post('/bk-logs', [BKLogApiController::class, 'store']);
    Route::get('/bk-logs', [BKLogApiController::class, 'index']);
    Route::put('/bk-logs/{id}', [BKLogApiController::class, 'update']);
    Route::delete('/bk-logs/{id}', [BKLogApiController::class, 'destroy']);
});

Route::post('/login-client', [\App\Http\Controllers\Api\ClientLoginController::class, 'login']);

