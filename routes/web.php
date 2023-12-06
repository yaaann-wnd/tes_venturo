<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

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
// route halaman beranda
Route::get('/', [TransaksiController::class, 'home'])->name('home');

// route menampilkan transaksi
Route::post('/transaksi', [TransaksiController::class, 'transaksi'])->name('transaksi');