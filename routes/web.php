<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekomendasiLombaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\TesRekomendasi;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DosenBimbinganController;
use App\Http\Controllers\DosenLombaController;
use App\Http\Controllers\VerifikasiLombaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\LaporanPrestasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BobotKriteriaController;


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

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter (id), maka harus berupa angka
Route::get('landing', [AuthController::class, 'landing'])->name('landing');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postregister']);

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu
    // jangan lupa nanti dimodifikasi sesusai dengan kebutuhan, terus kasih comment kalau sekiranya butuh
    Route::get('/', function () {
        if (auth()->user()->role == 'dosen') {
            return app(DashboardController::class)->indexDosen();
        } else if (auth()->user()->role == 'mahasiswa') {
            return app(DashboardController::class)->indexMahasiswa();
        }
        return app(DashboardController::class)->index();
    });
    // Route::get('/rekomendasi', [RekomendasiLombaController::class, 'index'])->name('rekomendasi.index');
    // Route::post('/rekomendasi/{id}', [RekomendasiLombaController::class, 'updateStatus'])->name('rekomendasi.updateStatus');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk admin
Route::middleware(['authorize:admin'])->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index']);                          // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);                      // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);         // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);                // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);        // menampilkan detail user Ajax
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);        // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);    // Menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    });

    Route::group(['prefix' => 'periode'], function () {
        Route::get('/', [PeriodeController::class, 'index']);                          // menampilkan halaman awal periode
        Route::post('/list', [PeriodeController::class, 'list']);                      // menampilkan data periode dalam bentuk json untuk datatables
        Route::get('/create_ajax', [PeriodeController::class, 'create_ajax']);         // Menampilkan halaman form tambah periode Ajax
        Route::post('/ajax', [PeriodeController::class, 'store_ajax']);                // Menyimpan data periode baru Ajax
        Route::get('/{id}/show_ajax', [PeriodeController::class, 'show_ajax']);        // menampilkan detail periode Ajax
        Route::get('/{id}/edit_ajax', [PeriodeController::class, 'edit_ajax']);        // Menampilkan halaman form edit periode Ajax
        Route::put('/{id}/update_ajax', [PeriodeController::class, 'update_ajax']);    // Menyimpan perubahan data periode Ajax
        Route::get('/{id}/delete_ajax', [PeriodeController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete periode Ajax
        Route::delete('/{id}/delete_ajax', [PeriodeController::class, 'delete_ajax']); // Untuk hapus data periode Ajax
    });

    Route::group(['prefix' => 'prestasi'], function () {
        Route::post('/{id}/approve_ajax', [PrestasiController::class, 'approve_ajax']);
        Route::post('/{id}/reject_ajax', [PrestasiController::class, 'reject_ajax']);
        Route::get('/mahasiswa/search', [PrestasiController::class, 'search']);
    });

    Route::group(['prefix' => 'verifikasi_lomba'], function () {
        Route::get('/', [VerifikasiLombaController::class, 'index']);                          // menampilkan halaman awal verifikasi lomba
        Route::post('/list', [VerifikasiLombaController::class, 'list']);                      // menampilkan data lomba dalam bentuk json untuk datatables
        Route::get('/{id}/show_ajax', [VerifikasiLombaController::class, 'show_ajax']);        // menampilkan detail lomba Ajax
        Route::post('/{id}/approve', [VerifikasiLombaController::class, 'approve']);
        Route::post('/{id}/reject', [VerifikasiLombaController::class, 'reject']);

    });

    Route::group(['prefix' => 'program_studi'], function () {
        Route::get('/', [ProgramStudiController::class, 'index']);                            // Menampilkan halaman daftar program studi
        Route::post('/list', [ProgramStudiController::class, 'list']);                        // Menampilkan data program studi dalam bentuk JSON
        Route::get('/create_ajax', [ProgramStudiController::class, 'create_ajax']);           // Menampilkan form tambah program studi Ajax
        Route::post('/store_ajax', [ProgramStudiController::class, 'store_ajax']);            // Menyimpan data program studi baru Ajax
        Route::get('/{id}/show_ajax', [ProgramStudiController::class, 'show_ajax']);          // Menampilkan detail program studi Ajax
        Route::get('/{id}/edit_ajax', [ProgramStudiController::class, 'edit_ajax']);          // Menampilkan form edit program studi Ajax
        Route::put('/{id}/update_ajax', [ProgramStudiController::class, 'update_ajax']);      // Menyimpan perubahan data program studi Ajax
        Route::get('/{id}/confirm_ajax', [ProgramStudiController::class, 'confirm_ajax']);    // Menampilkan form konfirmasi delete Ajax
        Route::delete('/{id}/delete_ajax', [ProgramStudiController::class, 'delete_ajax']);   // Menghapus data program studi Ajax
    });

    Route::prefix('admin')->group(function () {
        // Halaman utama laporan prestasi
        Route::get('laporan-prestasi', [LaporanPrestasiController::class, 'index'])->name('laporan-prestasi.index');
        // Route untuk DataTables AJAX (POST)
        Route::post('laporan-prestasi/list', [LaporanPrestasiController::class, 'list'])->name('laporan-prestasi.list');
        // Route untuk export
        Route::get('laporan-prestasi-export-excel', [LaporanPrestasiController::class, 'exportExcel'])->name('laporan-prestasi.exportExcel');
        Route::get('laporan-prestasi-export-pdf', [LaporanPrestasiController::class, 'exportPdf'])->name('laporan-prestasi.exportPdf');
    });

    Route::get('/bobot', [BobotKriteriaController::class, 'index']);
    Route::post('/bobot', [BobotKriteriaController::class, 'update']);


    //  Route::resource('laporan-prestasi', LaporanPrestasiController::class)->only(['index']);



    Route::get('/tes-rekomendasi/topsis', [TesRekomendasi::class, 'prosesSemuaLombaDenganTopsis']); //jangan dipake dulu masih belum stabil
    Route::get('/tes-topsis/{idLomba}', [TesRekomendasi::class, 'lihatHasilTopsis']);
});


// Route untuk mahasiswa dan admin
Route::middleware(['authorize:mahasiswa,admin'])->group(function () {
    Route::group(['prefix' => 'prestasi'], function () {
        Route::get('/', [PrestasiController::class, 'index']);                          // menampilkan halaman awal prestasi
        Route::post('/list', [PrestasiController::class, 'list']);                      // menampilkan data prestasi dalam bentuk json untuk datatables
        Route::get('/create_ajax', [PrestasiController::class, 'create_ajax']);         // Menampilkan halaman form tambah prestasi Ajax
        Route::post('/ajax', [PrestasiController::class, 'store_ajax']);                // Menyimpan data prestasi baru Ajax
        Route::get('/{id}/show_ajax', [PrestasiController::class, 'show_ajax'])->name('prestasi.show_ajax');        // menampilkan detail prestasi Ajax
        Route::get('/{id}/edit_ajax', [PrestasiController::class, 'edit_ajax']);        // Menampilkan halaman form edit prestasi Ajax
        Route::put('/{id}/update_ajax', [PrestasiController::class, 'update_ajax']);    // Menyimpan perubahan data prestasi Ajax
        Route::get('/{id}/delete_ajax', [PrestasiController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete prestasi Ajax
        Route::delete('/{id}/delete_ajax', [PrestasiController::class, 'delete_ajax']); // Untuk hapus data prestasi Ajax
        Route::get('/lomba/{id}/detail', [PrestasiController::class, 'getDetail']);
        Route::get('/{id}/show', [PrestasiController::class, 'show'])->name('prestasi.show');

    });
});

// route untuk mahasiswa, dan dosen
Route::middleware(['authorize:mahasiswa,dosen'])->group(function () {
    Route::group(['prefix' => 'dosen/lomba'], function () {
        Route::get('/', [DosenLombaController::class, 'index']);                          // menampilkan halaman awal lomba dosen
        Route::post('/list', [DosenLombaController::class, 'list']);                      // datatables
        Route::get('/create_ajax', [DosenLombaController::class, 'create_ajax']);         // form tambah ajax
        Route::post('/ajax', [DosenLombaController::class, 'store_ajax']);                // simpan data lomba
        Route::get('/{id}/show_ajax', [DosenLombaController::class, 'show_ajax']);
    });
});

// route untuk dosen
Route::middleware(['authorize:dosen'])->group(function () {
    Route::group(['prefix' => 'dosen'], function () {
        Route::get('/bimbingan', [DosenBimbinganController::class, 'index'])->name('dosen.bimbingan.index'); // Halaman daftar mahasiswa bimbingan
        Route::get('/bimbingan/{nim}', [DosenBimbinganController::class, 'show'])->name('dosen.bimbingan.show'); // Detail prestasi mahasiswa bimbingan
        Route::post('/bimbingan/list', [DosenBimbinganController::class, 'list'])->name('dosen.bimbingan.list'); // Endpoint DataTables AJAX untuk daftar mahasiswa bimbingan
    });


    Route::group(['prefix' => 'rekomendasi'], function () {
        Route::post('/{id}/approve_dosen', [RekomendasiLombaController::class, 'approveDosen']);
        Route::post('/{id}/reject_dosen', [RekomendasiLombaController::class, 'rejectDosen']);
    });
});


// seluruh user bisa akses
Route::middleware(['authorize:mahasiswa,admin,dosen'])->group(function () {

    Route::group(['prefix' => 'rekomendasi'], function () {
        Route::get('/', [RekomendasiLombaController::class, 'index']);                          // menampilkan halaman awal prestasi
        Route::post('/list', [RekomendasiLombaController::class, 'list']);
        Route::get('/list-all', [RekomendasiLombaController::class, 'listAll']);                      // menampilkan data prestasi dalam bentuk json untuk datatables
        Route::get('/create_ajax', [RekomendasiLombaController::class, 'create_ajax']);         // Menampilkan halaman form tambah prestasi Ajax
        Route::post('/ajax', [RekomendasiLombaController::class, 'store_ajax']);                // Menyimpan data prestasi baru Ajax
        Route::get('/{id}/show_ajax', [RekomendasiLombaController::class, 'show_ajax']);        // menampilkan detail prestasi Ajax
        Route::get('/{id}/edit_ajax', [RekomendasiLombaController::class, 'edit_ajax']);        // Menampilkan halaman form edit prestasi Ajax
        Route::put('/{id}/update_ajax', [RekomendasiLombaController::class, 'update_ajax']);    // Menyimpan perubahan data prestasi Ajax
        Route::get('/{id}/delete_ajax', [RekomendasiLombaController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete prestasi Ajax
        Route::delete('/{id}/delete_ajax', [RekomendasiLombaController::class, 'delete_ajax']); // Untuk hapus data prestasi Ajax
        Route::get('/lomba/{id}/detail', [RekomendasiLombaController::class, 'getDetail']);
        Route::post('/{id}/approve_ajax', [RekomendasiLombaController::class, 'approve']);
        Route::post('/{id}/reject_ajax', [RekomendasiLombaController::class, 'reject']);
        Route::get('/{id}/approve_ajax', [RekomendasiLombaController::class, 'approve']);
        Route::get('/{id}/reject_ajax', [RekomendasiLombaController::class, 'reject']);
        Route::get('/dosen/{id}/detail', [RekomendasiLombaController::class, 'getDetail']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/read/{notification_id}', [NotificationController::class, 'read'])->name('notifikasi.read');
        Route::get('/notifikasi/unreaded', [NotificationController::class, 'getUnreaded'])->name('notifikasi.unreaded');
        Route::post('/notifikasi/readall', [NotificationController::class, 'readall'])->name('notifikasi.readall');
    });

    Route::group(['prefix' => 'lomba'], function () {
        Route::get('/', [LombaController::class, 'index']);                          // menampilkan halaman awal lomba
        Route::post('/list', [LombaController::class, 'list']);                      // menampilkan data lomba dalam bentuk json untuk datatables
        Route::get('/create_ajax', [LombaController::class, 'create_ajax']);         // Menampilkan halaman form tambah lomba Ajax
        Route::post('/ajax', [LombaController::class, 'store_ajax']);                // Menyimpan data lomba baru Ajax
        Route::get('/{id}/show_ajax', [LombaController::class, 'show_ajax']);        // menampilkan detail lomba Ajax
        Route::get('/{id}/edit_ajax', [LombaController::class, 'edit_ajax']);        // Menampilkan halaman form edit lomba Ajax
        Route::put('/{id}/update_ajax', [LombaController::class, 'update_ajax']);    // Menyimpan perubahan data lomba Ajax
        Route::get('/{id}/delete_ajax', [LombaController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete lomba Ajax
        Route::delete('/{id}/delete_ajax', [LombaController::class, 'delete_ajax']); // Untuk hapus data lomba Ajax
    });
});

Route::middleware(['authorize:mahasiswa'])->group(function () {
    Route::group(['prefix' => 'pendaftaran'], function () {
        Route::get('/', [PendaftaranController::class, 'index']);                          // menampilkan halaman awal pendaftaran lomba
        Route::post('/list', [PendaftaranController::class, 'list']);                      // menampilkan data pendaftaran lomba dalam bentuk json untuk datatables
        Route::get('/create_ajax', [PendaftaranController::class, 'create_ajax']);         // Menampilkan halaman form tambah pendaftaran lomba Ajax
        Route::post('/ajax', [PendaftaranController::class, 'store_ajax']);                // Menyimpan data pendaftaran lomba baru Ajax
        Route::get('/{id}/show_ajax', [PendaftaranController::class, 'show_ajax']);        // menampilkan detail pendaftaran lomba Ajax
        Route::get('/{id}/edit_ajax', [PendaftaranController::class, 'edit_ajax']);        // Menampilkan halaman form edit p
        Route::put('/{id}/update_ajax', [PendaftaranController::class, 'update_ajax']);    // Menyimpan perubahan data pendaftaran lomba Ajax
        Route::get('/{id}/delete_ajax', [PendaftaranController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete pendaftaran lomba Ajax
        Route::delete('/{id}/delete_ajax', [PendaftaranController::class, 'delete_ajax']); // Untuk hapus data pendaftaran lomba Ajax
        Route::get('/{id}/detail', [PendaftaranController::class, 'getDetail']);
    });
});