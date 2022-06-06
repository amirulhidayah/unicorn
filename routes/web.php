<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\masterData\AkunController;
use App\Http\Controllers\dashboard\masterData\BiroOrganisasiController;
use App\Http\Controllers\dashboard\masterData\DokumenSppGuController;
use App\Http\Controllers\dashboard\masterData\DokumenSppLsController;
use App\Http\Controllers\dashboard\masterData\DokumenSppTuController;
use App\Http\Controllers\dashboard\masterData\DokumenSppUpController;
use App\Http\Controllers\dashboard\masterData\TahunController;
use App\Http\Controllers\dashboard\spd\SpdController;
use App\Http\Controllers\dashboard\spp\SppGuController;
use App\Http\Controllers\dashboard\spp\SppLsController;
use App\Http\Controllers\dashboard\spp\SppTuController;
use App\Http\Controllers\dashboard\spp\SppUpController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UnduhController;
use App\Models\DaftarDokumenSppUp;
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
    Route::resource('/master-data/tahun', TahunController::class);
    Route::resource('/master-data/daftar-dokumen-spp-up', DokumenSppUpController::class);
    Route::resource('/master-data/daftar-dokumen-spp-tu', DokumenSppTuController::class);
    Route::resource('/master-data/daftar-dokumen-spp-ls', DokumenSppLsController::class)->parameters([
        'daftar-dokumen-spp-ls' => 'daftar-dokumen-spp-ls'
    ]);
    Route::resource('/master-data/daftar-dokumen-spp-gu', DokumenSppGuController::class);

    // SPP UP
    Route::resource('/spp-up', SppUpController::class)->except([
        'edit'
    ]);
    Route::get('/spp-up/riwayat/{sppUp}', [SppUpController::class, 'riwayat']);
    Route::put('/spp-up/verifikasi/{sppUp}', [SppUpController::class, 'verifikasi']);
    Route::post('/spp-up/{spp_up}/edit', [SppUpController::class, 'edit']);
    Route::get('/surat-penolakan/spp-up/{riwayatSppUp}', [UnduhController::class, 'suratPenolakanSppUp']);

    // SPP TU
    Route::resource('/spp-tu', SppTuController::class)->except([
        'edit'
    ]);
    Route::post('/spp-tu/{spp_tu}/edit', [SppTuController::class, 'edit']);
    Route::put('/spp-tu/verifikasi/{sppTu}', [SppTuController::class, 'verifikasi']);
    Route::get('/spp-tu/riwayat/{sppTu}', [SppTuController::class, 'riwayat']);
    Route::get('/surat-penolakan/spp-tu/{riwayatSppTu}', [UnduhController::class, 'suratPenolakanSppTu']);

    // SPP LS
    Route::resource('/spp-ls', SppLsController::class)->except([
        'edit'
    ])->parameters([
        'spp-ls' => 'spp-ls'
    ]);
    Route::post('/spp-ls/{sppLs}/edit', [SppLsController::class, 'edit']);
    Route::put('/spp-ls/verifikasi-akhir/{sppLs}', [SppLsController::class, 'verifikasiAkhir']);
    Route::put('/spp-ls/verifikasi/{sppLs}', [SppLsController::class, 'verifikasi']);
    Route::get('/spp-ls/riwayat/{sppLs}', [SppLsController::class, 'riwayat']);
    Route::get('/surat-penolakan/spp-ls/{riwayatSppLs}', [UnduhController::class, 'suratPenolakanSppLs']);

    // SPP GU
    Route::get('/spp-gu/create/{sppGu}', [SppGuController::class, 'createTahapAkhir']);
    Route::post('/spp-gu/{sppGu}', [SppGuController::class, 'storeTahapAkhir']);
    Route::resource('/spp-gu', SppGuController::class)->except([
        'edit'
    ]);
    Route::post('/spp-gu/{sppGu}/edit', [SppGuController::class, 'edit']);
    Route::put('/spp-gu/verifikasi-akhir/{sppGu}', [SppGuController::class, 'verifikasiAkhir']);
    Route::put('/spp-gu/verifikasi/{sppGu}', [SppGuController::class, 'verifikasi']);
    Route::get('/spp-gu/riwayat/{sppGu}', [SppGuController::class, 'riwayat']);
    Route::get('/surat-penolakan/spp-gu/{riwayatSppGu}', [UnduhController::class, 'suratPenolakanSppGu']);

    Route::post('/spd/tabel-spd', [SpdController::class, 'tabelSpd']);
    Route::post('/spd/get-spd', [SpdController::class, 'getSpd']);
    Route::get('/spd/format-import', [SpdController::class, 'formatImport']);
    Route::post('/spd/import', [SpdController::class, 'importSpd']);
    Route::resource('/spd', SpdController::class);

    Route::get('/logout', [AuthController::class, 'logout']);

    // List
    Route::post('/list/dokumen-spp-ls', [ListController::class, 'dokumenSppLs']);
    Route::post('/list/program', [ListController::class, 'program']);
    Route::post('/list/kegiatan', [ListController::class, 'kegiatan']);
});

Route::resource('/master-data/akun', AkunController::class);
// Auth
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
