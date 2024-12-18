<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang', [App\Http\Controllers\TentangController::class, 'index']);
Route::get('/kontak', [App\Http\Controllers\KontakController::class, 'index']);
Route::get('/bergabung', [App\Http\Controllers\BergabungController::class, 'index']);
Route::get('/terima-kasih', [TerimaKasihController::class, 'index'])->name('terima.kasih.index');

