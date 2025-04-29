<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

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


/**Route::get('/level', [LevelController::class, 'index']);

Route::get('/kategori', [KategoriController::class, 'index']);

Route::get('/user', [UserController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);

Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);

Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);

Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan'])->name('user.ubah_simpan');

Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);**/

Route::pattern('id', '[0-9]+'); //artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

//Route::middleware(['authorize:ADM,MNG,STF,SLS,SPV,SMD'])->group(function () {

    Route::get('/', [WelcomeController::class, 'index']);

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/list', [UserController::class, 'list']);
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);
        Route::post('/ajax', [UserController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
        Route::get('/import', [UserController::class, 'import']); //ajax form upload excel
        Route::post('/import_ajax', [UserController::class, 'import_ajax']); //ajax import excel
        Route::get('/export_excel', [UserController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']); // export pdf
    });

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
        Route::get('/import', [LevelController::class, 'import']); //ajax form upload excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); //ajax import excel
        Route::get('/export_excel', [LevelController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // export pdf
    });

    Route::group(['prefix' => 'kategori', 'middleware' => 'authorize:ADM,MNG'], function () {
        Route::get('/', [KategoriController::class, 'index']);
        Route::post('/list', [KategoriController::class, 'list']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
        Route::get('/import', [KategoriController::class, 'import']); //ajax form upload excel
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']); //ajax import excel
        Route::get('/export_excel', [KategoriController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']); // export pdf
    });

    Route::group(['prefix' => 'supplier', 'middleware' => 'authorize:ADM,MNG'], function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/list', [SupplierController::class, 'list']);
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
        Route::get('/import', [SupplierController::class, 'import']); //ajax form upload excel
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); //ajax import excel
        Route::get('/export_excel', [SupplierController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); // export pdf
    });

    Route::group(['prefix' => 'barang', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list', [BarangController::class, 'list']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
        Route::get('/import', [BarangController::class, 'import']); //ajax form upload excel
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [BarangController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']); // export pdf
    });

    Route::group(['prefix' => 'profil', 'middleware' => 'authorize:ADM,MNG,STF,SLS,SPV,SMD'], function () {
        Route::get('/', [ProfilController::class, 'index'])->name('profil.index');
        Route::get('/edit', [ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/update', [ProfilController::class, 'update'])->name('profil.update');
        Route::get('/hapus-foto', [ProfilController::class, 'deleteFoto'])->name('profil.deleteFoto');

    });

    Route::group(['prefix' => 'stok', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [StokController::class, 'index']);
        Route::post('/list', [StokController::class, 'list']);
        Route::get('/create_ajax', [StokController::class, 'create_ajax']);
        Route::post('/ajax', [StokController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);
        Route::get('/import', [StokController::class, 'import']); //ajax form upload excel
        Route::post('/import_ajax', [StokController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [StokController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [StokController::class, 'export_pdf']); // export pdf
    });

    Route::group(['prefix' => 'penjualan', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [PenjualanController::class, 'index']);
        Route::post('/list', [PenjualanController::class, 'list']);
        Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']);
        Route::post('/ajax', [PenjualanController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax']);
        Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']);
        Route::get('/export_excel', [PenjualanController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']); // export pdf
    });
