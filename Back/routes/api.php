<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControllerArea;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('custom.auth')->group(function () {
    Route::get('/test', [TestController::class, 'test']);

    // area
    Route::get('/area', [ControllerArea::class, 'index']);
    Route::get('/area/{id}', [ControllerArea::class, 'show']);
    Route::post('/area', [ControllerArea::class, 'create']);
    Route::delete('/area/{id}', [ControllerArea::class, 'delete']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);