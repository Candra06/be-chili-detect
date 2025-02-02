<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\HasilController;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class,'login']);
Route::post('/login',[AuthController::class,'submitLogin']);
Route::group(["prefix" => "api",], function () {
    Route::get('/list-gejala', [GejalaController::class, 'listGejala']);
    Route::get('/list-penyakit', [PenyakitController::class, 'listPenyakit']);
    Route::post('execute-ann', [HasilController::class,'executeModel']);
    Route::post('validate-diagnose', [HasilController::class,'validateDiagnose']);
});
Route::group(["prefix" => "/", "middleware" => ["auth"]], function () {
    Route::get('/dashboard', [AuthController::class,'dashboard']);
    Route::get('/logout', [AuthController::class,'logout']);
    Route::resource('gejala', GejalaController::class);
    Route::resource('penyakit', PenyakitController::class);
    Route::resource('rules', RulesController::class);
});
