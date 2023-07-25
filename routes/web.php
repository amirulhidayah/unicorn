<?php

use App\Http\Controllers\AppendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\dpa\StatistikDpaController;
use App\Http\Controllers\dashboard\dpa\TabelDpaController;
use App\Http\Controllers\dashboard\masterData\AkunController;
use App\Http\Controllers\dashboard\masterData\AkunLainnyaController;
use App\Http\Controllers\dashboard\masterData\SekretariatDaerahController;
use App\Http\Controllers\dashboard\masterData\DokumenSppGuController;
use App\Http\Controllers\dashboard\masterData\DokumenSppLsController;
use App\Http\Controllers\dashboard\masterData\DokumenSppTuController;
use App\Http\Controllers\dashboard\masterData\DokumenSppUpController;
use App\Http\Controllers\dashboard\masterData\KegiatanDpaController;
use App\Http\Controllers\dashboard\masterData\KegiatanSppController;
use App\Http\Controllers\dashboard\masterData\ProgramDpaController;
use App\Http\Controllers\dashboard\masterData\ProgramSppController;
use App\Http\Controllers\dashboard\masterData\TahunController;
use App\Http\Controllers\dashboard\masterData\TentangController;
use App\Http\Controllers\dashboard\spd\SpdController;
use App\Http\Controllers\dashboard\spp\SppGuController;
use App\Http\Controllers\dashboard\spp\SppLsController;
use App\Http\Controllers\dashboard\spp\SppTuController;
use App\Http\Controllers\dashboard\spp\SppUpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfilController;
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

Route::get('/', [LandingController::class, 'index']);
Route::get('/tentang', [LandingController::class, 'tentang']);
// Master Data
Route::group(['middleware' => ['auth']], function () {
    Route::get('spp-up/cek-sp2d', [SppUpController::class, 'cekSp2d']);
    Route::get('spp-tu/cek-sp2d', [SppTuController::class, 'cekSp2d']);
    Route::get('spp-ls/cek-sp2d', [SppLsController::class, 'cekSp2d']);
    Route::get('spp-gu/cek-sp2d', [SppGuController::class, 'cekSp2d']);

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::resource('/master-data/sekretariat-daerah', SekretariatDaerahController::class);
        Route::resource('/master-data/tahun', TahunController::class);
        Route::resource('/master-data/daftar-dokumen-spp-up', DokumenSppUpController::class);
        Route::resource('/master-data/daftar-dokumen-spp-tu', DokumenSppTuController::class);
        Route::resource('/master-data/daftar-dokumen-spp-ls', DokumenSppLsController::class)->parameters([
            'daftar-dokumen-spp-ls' => 'daftar-dokumen-spp-ls'
        ]);
        Route::resource('/master-data/daftar-dokumen-spp-gu', DokumenSppGuController::class);

        Route::resource('/master-data/program-dpa', ProgramDpaController::class)->parameters([
            'program-dpa' => 'program_dpa'
        ]);

        Route::resource('/master-data/kegiatan-dpa/{program}', KegiatanDpaController::class)->parameters([
            '{program}' => 'kegiatan_dpa'
        ]);

        Route::resource('/master-data/akun', AkunController::class)->parameters([
            'akun' => 'user'
        ]);
        Route::resource('/master-data/akun-lainnya', AkunLainnyaController::class)->parameters([
            'akun-lainnya' => 'user'
        ]);

        Route::get('/tabel-dpa/format-import', [TabelDpaController::class, 'formatImport']);
        Route::post('/tabel-dpa/import', [TabelDpaController::class, 'importSpd']);
    });

    Route::resource('/master-data/program-spp', ProgramSppController::class)->parameters([
        'program-spp' => 'programSpp'
    ]);

    Route::resource('/master-data/kegiatan-spp/{programSpp}', KegiatanSppController::class)->parameters([
        '{programSpp}' => 'kegiatanSpp'
    ]);

    Route::group(['middleware' => ['role:Admin|Bendahara Pengeluaran|Bendahara Pengeluaran Pembantu|Bendahara Pengeluaran Pembantu Belanja Hibah']], function () {
        // SPP UP
        Route::resource('/spp-up', SppUpController::class)->except(
            'index',
            'show'
        );

        // SPP TU
        Route::resource('/spp-tu', SppTuController::class)->except(
            'index',
            'show'
        );

        // SPP LS
        Route::resource('/spp-ls', SppLsController::class)->parameters([
            'spp-ls' => 'spp-ls'
        ])->except(
            'index',
            'show'
        );

        // SPP GU
        Route::get('/spp-gu/create/{sppGu}', [SppGuController::class, 'createTahapSpp']);
        Route::post('/spp-gu/{sppGu}', [SppGuController::class, 'storeTahapSpp']);
        Route::resource('/spp-gu', SppGuController::class)->except(
            'index',
            'show'
        );
    });

    // SPP UP
    Route::group(['middleware' => ['role:Admin|PPK|ASN Sub Bagian Keuangan|Kuasa Pengguna Anggaran|Operator SPM']], function () {
    });
    Route::get('/spp-up/riwayat/{sppUp}', [SppUpController::class, 'riwayat']);
    Route::get('/spp-tu/riwayat/{sppTu}', [SppTuController::class, 'riwayat']);
    Route::get('/spp-ls/riwayat/{sppLs}', [SppLsController::class, 'riwayat']);
    Route::get('/spp-gu/riwayat/{sppGu}', [SppGuController::class, 'riwayat']);

    Route::resource('/spp-up', SppUpController::class)->only(
        'index',
        'show'
    );

    Route::group(['middleware' => ['role:PPK|ASN Sub Bagian Keuangan']], function () {
        Route::put('/spp-up/verifikasi/{sppUp}', [SppUpController::class, 'verifikasi']);
    });

    Route::group(['middleware' => ['role:PPK']], function () {
        Route::put('/spp-up/verifikasi-akhir/{sppUp}', [SppUpController::class, 'verifikasiAkhir']);
    });

    Route::group(['middleware' => ['role:Admin|Operator SPM']], function () {
        Route::put('spp-up/spm/{sppUp}', [SppUpController::class, 'storeSpm']);
        Route::put('spp-tu/spm/{sppTu}', [SppTuController::class, 'storeSpm']);
        Route::put('spp-ls/spm/{sppLs}', [SppLsController::class, 'storeSpm']);
        Route::put('spp-gu/spm/{sppGu}', [SppGuController::class, 'storeSpm']);
    });

    Route::group(['middleware' => ['role:Admin|Bendahara Pengeluaran|Bendahara Pengeluaran Pembantu|Bendahara Pengeluaran Pembantu Belanja Hibah']], function () {
        Route::put('spp-up/sp2d/{sppUp}', [SppUpController::class, 'storeSp2d']);
        Route::put('spp-tu/sp2d/{sppTu}', [SppTuController::class, 'storeSp2d']);
        Route::put('spp-ls/sp2d/{sppLs}', [SppLsController::class, 'storeSp2d']);
        Route::put('spp-gu/sp2d/{sppGu}', [SppGuController::class, 'storeSp2d']);
    });

    Route::get('/surat-penolakan/spp-up/{sppUp}/{tahapRiwayat}', [UnduhController::class, 'suratPenolakanSppUp']);
    Route::get('/surat-pernyataan/spp-up/{sppUp}', [UnduhController::class, 'suratPernyataanSppUp']);

    // SPP TU
    Route::resource('/spp-tu', SppTuController::class)->only(
        'index',
        'show'
    );

    Route::group(['middleware' => ['role:PPK|ASN Sub Bagian Keuangan']], function () {
        Route::put('/spp-tu/verifikasi/{sppTu}', [SppTuController::class, 'verifikasi']);
    });

    Route::group(['middleware' => ['role:PPK']], function () {
        Route::put('/spp-tu/verifikasi-akhir/{sppTu}', [SppTuController::class, 'verifikasiAkhir']);
    });

    Route::get('/surat-penolakan/spp-tu/{sppTu}/{tahapRiwayat}', [UnduhController::class, 'suratPenolakanSppTu']);
    Route::get('/surat-pernyataan/spp-tu/{sppTu}', [UnduhController::class, 'suratPernyataanSppTu']);

    // SPP LS
    Route::resource('/spp-ls', SppLsController::class)->parameters([
        'spp-ls' => 'spp-ls'
    ])->only(
        'index',
        'show'
    );

    Route::group(['middleware' => ['role:PPK|ASN Sub Bagian Keuangan']], function () {
        Route::put('/spp-ls/verifikasi/{sppLs}', [SppLsController::class, 'verifikasi']);
    });

    Route::group(['middleware' => ['role:PPK']], function () {
        Route::put('/spp-ls/verifikasi-akhir/{sppLs}', [SppLsController::class, 'verifikasiAkhir']);
    });

    Route::get('/surat-penolakan/spp-ls/{sppLs}/{tahapRiwayat}', [UnduhController::class, 'suratPenolakanSppLs']);
    Route::get('/surat-pernyataan/spp-ls/{sppLs}', [UnduhController::class, 'suratPernyataanSppLs']);

    // SPP GU
    Route::resource('/spp-gu', SppGuController::class)->only(
        'index',
        'show'
    );

    Route::group(['middleware' => ['role:PPK|ASN Sub Bagian Keuangan']], function () {
        Route::put('/spp-gu/verifikasi/{sppGu}', [SppGuController::class, 'verifikasi']);
    });

    Route::group(['middleware' => ['role:PPK']], function () {
        Route::put('/spp-gu/verifikasi-akhir/{sppGu}', [SppGuController::class, 'verifikasiAkhir']);
    });

    Route::get('/surat-penolakan/spp-gu/{sppGu}/{tahapRiwayat}', [UnduhController::class, 'suratPenolakanSppGu']);
    Route::get('/surat-pernyataan/spp-gu/{sppGu}', [UnduhController::class, 'suratPernyataanSppGu']);

    Route::post('/tabel-dpa/tabel-dpa', [TabelDpaController::class, 'tabelDpa']);
    Route::get('/tabel-dpa/tabel-dpaDebug', [TabelDpaController::class, 'tabelDpaDebug']);
    Route::post('/tabel-dpa/get-spd', [TabelDpaController::class, 'getSpd']);
    Route::post('/tabel-dpa/export', [TabelDpaController::class, 'exportSpd']);
    Route::resource('/tabel-dpa', TabelDpaController::class)->parameters([
        'tabel-dpa' => 'spd'
    ]);

    Route::get('/statistik-dpa', [StatistikDpaController::class, 'index']);
    Route::post('/statistik-dpa/get-data-statistik', [StatistikDpaController::class, 'getDataStatistik']);

    Route::get('/logout', [AuthController::class, 'logout']);

    // List
    Route::post('/list/dokumen-spp-ls', [ListController::class, 'dokumenSppLs']);
    Route::post('/list/program-dpa', [ListController::class, 'programDpa']);
    Route::post('/list/kegiatan-dpa', [ListController::class, 'kegiatanDpa']);
    Route::post('/list/program-spp', [ListController::class, 'programSpp']);
    Route::post('/list/kegiatan-spp', [ListController::class, 'kegiatanSpp']);

    Route::get('/profil', [ProfilController::class, 'index']);
    Route::put('/profil/{user}', [ProfilController::class, 'update']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Route::get('/dokumen_spp_gu/{dokumen}', [FileController::class, 'dokumenSppGu']);

    Route::get('/master-data/tentang', [TentangController::class, 'index']);
    Route::put('/master-data/tentang/{tentang}', [TentangController::class, 'update']);

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::prefix('append')->group(function () {
        Route::get('spp', [AppendController::class, 'spp']);
        Route::get('sppLs', [AppendController::class, 'sppLs']);
    });
});


// Auth
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
