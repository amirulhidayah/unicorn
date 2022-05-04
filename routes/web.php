<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\masterData\AkunController;
use App\Http\Controllers\dashboard\masterData\BiroOrganisasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return view('welcome');
});

// Master Data
Route::group(['middleware' => ['auth']], function () {
    Route::resource('/master-data/biro-organisasi', BiroOrganisasiController::class);

    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::resource('/master-data/akun', AkunController::class);
// Auth
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
