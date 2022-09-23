<?php

use App\Http\Controllers\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('mahasiswa', [MahasiswaController::class , 'index']);
Route::get('mahasiswa/show/{mahasiswa:slug}', [MahasiswaController::class , 'show']);
Route::post('mahasiswa/store', [MahasiswaController::class , 'store']);
Route::post('mahasiswa/destroy/{mahasiswa:slug}', [MahasiswaController::class , 'destroy']);
Route::post('mahasiswa/update/{mahasiswa:slug}', [MahasiswaController::class , 'update']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
