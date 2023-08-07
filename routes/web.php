<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
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

Route::middleware(['guest:karyawan'])->group(function(){
    // Halaman Login
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    // Proses login
    Route::post('/proseslogin',[AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function(){
    // Halaman Login
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    // Proses login
    Route::post('/prosesloginadmin',[AuthController::class, 'prosesloginadmin']);
});

// Tipe login karyawan
Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index']);
    Route::get('/proseslogout',[AuthController::class, 'proseslogout']);

    // Presensi
    Route::get('/presensi/create',[PresensiController::class, 'create']);
    Route::post('/presensi/store',[PresensiController::class, 'store'])->name('presensi.add');

    // Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);

    // Histori Presensi
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    // Form Izin atau Sakit
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
});

// Tipe login user (admin)
Route::middleware(['auth:user'])->group(function(){
    Route::get('/panel/dashboardadmin',[DashboardController::class, 'dashboardadmin']);
    Route::get('/proseslogoutadmin',[AuthController::class, 'proseslogoutadmin']);

    // Pengelolaan data karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);

    // Monitoring Presensi Karyawan
    Route::get('/presensi/monitoring',[PresensiController::class,'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);

    // Validasi Pengajuan Izin / Sakit
    Route::get('/presensi/izinsakit',[PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit',[PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit',[PresensiController::class, 'batalkanizinsakit']);
});