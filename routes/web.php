<?php

use App\Http\Controllers\AppendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\dpa\StatistikPelaksanaanAnggaranController;
use App\Http\Controllers\dashboard\dpa\SuratPenyediaanDanaController;
use App\Http\Controllers\dashboard\dpa\TabelDpaController;
use App\Http\Controllers\dashboard\dpa\TabelPelaksanaanAnggaranController;
use App\Http\Controllers\dashboard\masterData\AkunController;
use App\Http\Controllers\dashboard\masterData\AkunLainnyaController;
use App\Http\Controllers\dashboard\masterData\SekretariatDaerahController;
use App\Http\Controllers\dashboard\masterData\DokumenSppGuController;
use App\Http\Controllers\dashboard\masterData\DokumenSppLsController;
use App\Http\Controllers\dashboard\masterData\DokumenSppTuController;
use App\Http\Controllers\dashboard\masterData\DokumenSppUpController;
use App\Http\Controllers\dashboard\masterData\KategoriSppLsController;
use App\Http\Controllers\dashboard\masterData\KegiatanController;
use App\Http\Controllers\dashboard\masterData\ProgramController;
use App\Http\Controllers\dashboard\masterData\TahunController;
use App\Http\Controllers\dashboard\masterData\TentangController;
use App\Http\Controllers\dashboard\repositori\RepositoriSpjGuController;
use App\Http\Controllers\dashboard\repositori\RepositoriSppGuController;
use App\Http\Controllers\dashboard\repositori\RepositoriSppLsController;
use App\Http\Controllers\dashboard\repositori\RepositoriSppTuController;
use App\Http\Controllers\dashboard\repositori\RepositoriSppUpController;
use App\Http\Controllers\dashboard\spd\SpdController;
use App\Http\Controllers\dashboard\spp\SpjGuController;
use App\Http\Controllers\dashboard\spp\SppGuController;
use App\Http\Controllers\dashboard\spp\SppLsController;
use App\Http\Controllers\dashboard\spp\SppTuController;
use App\Http\Controllers\dashboard\spp\SppUpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScanqrcodeController;
use App\Http\Controllers\UnduhController;
use App\Models\DaftarDokumenSppUp;
use App\Models\KategoriSppLs;
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

Route::group(['middleware' => ['auth']], function () {
    Route::get('spp-up/cek-sp2d', [SppUpController::class, 'cekSp2d']);
    Route::get('spp-tu/cek-sp2d', [SppTuController::class, 'cekSp2d']);
    Route::get('spp-ls/cek-sp2d', [SppLsController::class, 'cekSp2d']);
    Route::get('spp-gu/cek-sp2d', [SppGuController::class, 'cekSp2d']);
    Route::get('spp-gu/qrcode-spj/{sppGu}', [SppGuController::class, 'qrcodeSpj']);

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::resource('/master-data/sekretariat-daerah', SekretariatDaerahController::class);
        Route::resource('/master-data/tahun', TahunController::class);
        Route::resource('/master-data/daftar-dokumen-spp-up', DokumenSppUpController::class);
        Route::resource('/master-data/daftar-dokumen-spp-tu', DokumenSppTuController::class);
        Route::resource('/master-data/daftar-dokumen-spp-ls', DokumenSppLsController::class)->parameters([
            'daftar-dokumen-spp-ls' => 'daftar-dokumen-spp-ls'
        ]);
        Route::resource('/master-data/daftar-dokumen-spp-gu', DokumenSppGuController::class);
        Route::resource('/master-data/kategori-spp-ls', KategoriSppLsController::class)->parameters([
            'kategori-spp-ls' => 'kategori-spp-ls'
        ]);
        Route::resource('/master-data/program', ProgramController::class)->parameters([
            'program' => 'program'
        ]);
        Route::resource('/master-data/kegiatan/{program}', KegiatanController::class)->parameters([
            '{program}' => 'kegiatan'
        ]);
        Route::resource('/master-data/akun', AkunController::class)->parameters([
            'akun' => 'user'
        ]);
        Route::resource('/master-data/akun-lainnya', AkunLainnyaController::class)->parameters([
            'akun-lainnya' => 'user'
        ]);

        Route::get('/surat-penyediaan-dana/format-import', [SuratPenyediaanDanaController::class, 'formatImport']);
        Route::post('/surat-penyediaan-dana/import', [SuratPenyediaanDanaController::class, 'import']);

        Route::get('/master-data/tentang', [TentangController::class, 'index']);
        Route::put('/master-data/tentang/{tentang}', [TentangController::class, 'update']);
        Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::resource('/surat-penyediaan-dana', SuratPenyediaanDanaController::class)->parameters([
            'surat-penyediaan-dana' => 'spd'
        ])->except(
            'index',
            'show'
        );
    });

    Route::group(['middleware' => ['role:Admin|Bendahara Pengeluaran|Bendahara Pengeluaran Pembantu|Bendahara Pengeluaran Pembantu Belanja Hibah']], function () {
        Route::resource('/spp-up', SppUpController::class)->except(
            'index',
            'show'
        );
        Route::resource('/spp-tu', SppTuController::class)->except(
            'index',
            'show'
        );
        Route::resource('/spp-ls', SppLsController::class)->parameters([
            'spp-ls' => 'spp-ls'
        ])->except(
            'index',
            'show'
        );
        Route::resource('/spj-gu', SpjGuController::class)->parameters([
            'spj-gu' => 'spj-gu'
        ])->except(
            'index',
            'show'
        );
        Route::resource('/spp-gu', SppGuController::class)->parameters([
            'spp-gu' => 'spp-gu'
        ])->except(
            'index',
            'show'
        );
        Route::put('spp-up/sp2d/{sppUp}', [SppUpController::class, 'storeSp2d']);
        Route::put('spp-tu/sp2d/{sppTu}', [SppTuController::class, 'storeSp2d']);
        Route::put('spp-ls/sp2d/{sppLs}', [SppLsController::class, 'storeSp2d']);
        Route::put('spp-gu/sp2d/{sppGu}', [SppGuController::class, 'storeSp2d']);
    });

    Route::group(['middleware' => ['role:Admin|PPK|ASN Sub Bagian Keuangan|Kuasa Pengguna Anggaran|Operator SPM']], function () {
        Route::group(['middleware' => ['role:PPK|ASN Sub Bagian Keuangan']], function () {
            Route::put('/spp-up/verifikasi/{sppUp}', [SppUpController::class, 'verifikasi']);
            Route::put('/spp-tu/verifikasi/{sppTu}', [SppTuController::class, 'verifikasi']);
            Route::put('/spp-ls/verifikasi/{sppLs}', [SppLsController::class, 'verifikasi']);
            Route::put('/spj-gu/verifikasi/{spjGu}', [SpjGuController::class, 'verifikasi']);
            Route::put('/spp-gu/verifikasi/{sppGu}', [SppGuController::class, 'verifikasi']);
        });

        Route::group(['middleware' => ['role:PPK']], function () {
            Route::put('/spp-up/verifikasi-akhir/{sppUp}', [SppUpController::class, 'verifikasiAkhir']);
            Route::put('/spp-tu/verifikasi-akhir/{sppTu}', [SppTuController::class, 'verifikasiAkhir']);
            Route::put('/spp-ls/verifikasi-akhir/{sppLs}', [SppLsController::class, 'verifikasiAkhir']);
            Route::put('/spj-gu/verifikasi-akhir/{spjGu}', [SpjGuController::class, 'verifikasiAkhir']);
            Route::put('/spp-gu/verifikasi-akhir/{sppGu}', [SppGuController::class, 'verifikasiAkhir']);
        });

        Route::group(['middleware' => ['role:Admin|Operator SPM']], function () {
            Route::put('spp-up/spm/{sppUp}', [SppUpController::class, 'storeSpm']);
            Route::put('spp-tu/spm/{sppTu}', [SppTuController::class, 'storeSpm']);
            Route::put('spp-ls/spm/{sppLs}', [SppLsController::class, 'storeSpm']);
            Route::put('spp-gu/spm/{sppGu}', [SppGuController::class, 'storeSpm']);
        });
    });

    Route::post('/surat-penyediaan-dana/tabel', [SuratPenyediaanDanaController::class, 'getTabel']);
    Route::resource('/surat-penyediaan-dana', SuratPenyediaanDanaController::class)->parameters([
        'surat-penyediaan-dana' => 'spd'
    ])->only(
        'index',
        'show'
    );

    Route::get('/spp-up/riwayat/{sppUp}', [SppUpController::class, 'riwayat']);
    Route::get('/spp-tu/riwayat/{sppTu}', [SppTuController::class, 'riwayat']);
    Route::get('/spp-ls/riwayat/{sppLs}', [SppLsController::class, 'riwayat']);
    Route::get('/spj-gu/riwayat/{spjGu}', [SpjGuController::class, 'riwayat']);
    Route::get('/spp-gu/riwayat/{sppGu}', [SppGuController::class, 'riwayat']);

    Route::resource('/spp-up', SppUpController::class)->only(
        'index',
        'show'
    );
    Route::resource('/spp-tu', SppTuController::class)->only(
        'index',
        'show'
    );
    Route::resource('/spp-ls', SppLsController::class)->parameters([
        'spp-ls' => 'spp-ls'
    ])->only(
        'index',
        'show'
    );
    Route::resource('/spj-gu', SpjGuController::class)->parameters([
        'spj-gu' => 'spj-gu'
    ])->only(
        'index',
        'show'
    );
    Route::resource('/spp-gu', SppGuController::class)->only(
        'index',
        'show'
    );

    Route::get('tabel-pelaksanaan-anggaran', [TabelPelaksanaanAnggaranController::class, 'index']);
    Route::post('tabel-pelaksanaan-anggaran/tabel', [TabelPelaksanaanAnggaranController::class, 'getTable']);
    Route::post('tabel-pelaksanaan-anggaran/export', [TabelPelaksanaanAnggaranController::class, 'export']);

    Route::get('/statistik-pelaksanaan-anggaran', [StatistikPelaksanaanAnggaranController::class, 'index']);
    Route::post('/statistik-pelaksanaan-anggaran/get-data', [StatistikPelaksanaanAnggaranController::class, 'getData']);

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::post('/list/dokumen-spp-ls', [ListController::class, 'dokumenSppLs']);
    Route::post('/list/program', [ListController::class, 'program']);
    Route::post('/list/kegiatan', [ListController::class, 'kegiatan']);
    Route::post('/list/program-spd', [ListController::class, 'programSpd']);
    Route::post('/list/kegiatan-spd', [ListController::class, 'kegiatanSpd']);
    Route::post('/list/spjGu', [ListController::class, 'spjGu']);

    Route::get('/profil', [ProfilController::class, 'index']);
    Route::put('/profil', [ProfilController::class, 'update']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/scan-qrcode', [ScanqrcodeController::class, 'index']);
    Route::post('/scan-qrcode', [ScanqrcodeController::class, 'getData']);

    Route::prefix('append')->group(function () {
        Route::get('spp', [AppendController::class, 'spp']);
        Route::get('spp-ls', [AppendController::class, 'sppLs']);
        Route::get('spj-gu', [AppendController::class, 'spjGu']);
        Route::get('spp-gu', [AppendController::class, 'sppGu']);
        Route::get('spp-up-tu', [AppendController::class, 'sppUpTu']);
    });

    Route::prefix('repositori')->group(function () {
        Route::prefix('spp-up')->group(function () {
            Route::get('/', [RepositoriSppUpController::class, 'index']);
            Route::get('/{sppUp}', [RepositoriSppUpController::class, 'show']);
            Route::get('/download-semua-berkas/{sppUp}', [RepositoriSppUpController::class, 'downloadSemuaBerkas']);
        });
        Route::prefix('spp-tu')->group(function () {
            Route::get('/', [RepositoriSppTuController::class, 'index']);
            Route::get('/{sppTu}', [RepositoriSppTuController::class, 'show']);
            Route::get('/download-semua-berkas/{sppTu}', [RepositoriSppTuController::class, 'downloadSemuaBerkas']);
        });
        Route::prefix('spp-ls')->group(function () {
            Route::get('/', [RepositoriSppLsController::class, 'index']);
            Route::get('/{sppLs}', [RepositoriSppLsController::class, 'show']);
            Route::get('/download-semua-berkas/{sppLs}', [RepositoriSppLsController::class, 'downloadSemuaBerkas']);
        });
        Route::prefix('spj-gu')->group(function () {
            Route::get('/', [RepositoriSpjGuController::class, 'index']);
            Route::get('/{spjGu}', [RepositoriSpjGuController::class, 'show']);
            Route::get('/download-semua-berkas/{spjGu}', [RepositoriSpjGuController::class, 'downloadSemuaBerkas']);
        });
        Route::prefix('spp-gu')->group(function () {
            Route::get('/', [RepositoriSppGuController::class, 'index']);
            Route::get('/{sppGu}', [RepositoriSppGuController::class, 'show']);
            Route::get('/download-semua-berkas/{sppGu}', [RepositoriSppGuController::class, 'downloadSemuaBerkas']);
        });
    });

    Route::prefix('get')->group(function () {
        Route::post('spd', [GetDataController::class, 'spd']);
    });
});

Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
