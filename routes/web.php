<?php

use App\Http\Controllers\AuthController;
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

// Public Routes (No Auth Required)
// Driver Point - View points only (QR Code 2D)
Route::get('/driver/point/{driverIdCard}', [App\Http\Controllers\DriverPointController::class, 'showPoints'])->name('driver.point');
// Driver Scan - Record transaction when bringing patient (Barcode 1D)
Route::get('/driver/scan/{driverIdCard}', [App\Http\Controllers\DriverPointController::class, 'scanTransaction'])->name('driver.scan');

// Auth Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    // Driver Routes
    Route::get('/driver/qrcode/all', [App\Http\Controllers\DriverController::class, 'qrcodeAll'])->name('driver.qrcode.all');
    Route::get('/driver/{driver}/qrcode', [App\Http\Controllers\DriverController::class, 'qrcodeSingle'])->name('driver.qrcode.single');
    Route::resource('driver', App\Http\Controllers\DriverController::class);
    
    // Scan Routes
    Route::prefix('scan')->name('scan.')->group(function () {
        Route::get('/', [App\Http\Controllers\ScanController::class, 'index'])->name('index');
        Route::post('/simulate', [App\Http\Controllers\ScanController::class, 'simulateScan'])->name('simulate');
        Route::get('/{id}/confirm', [App\Http\Controllers\ScanController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/confirm', [App\Http\Controllers\ScanController::class, 'processConfirm'])->name('process-confirm');
        Route::post('/{id}/reject', [App\Http\Controllers\ScanController::class, 'reject'])->name('reject');
        Route::get('/pending-count', [App\Http\Controllers\ScanController::class, 'getPendingCount'])->name('pending-count');
    });
    
    // Pasien Routes
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [App\Http\Controllers\PatientController::class, 'index'])->name('index');
    });
    
    // Patient Routes (alternative naming)
    Route::prefix('patient')->name('patient.')->group(function () {
        Route::get('/', [App\Http\Controllers\PatientController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\PatientController::class, 'show'])->name('show');
    });
    
    // Reward Routes
    Route::prefix('reward')->name('reward.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.index');
        })->name('index');
    });
});
