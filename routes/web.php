<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

Route::get('/', [TransaksiController::class,'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class,'store'])->name('transaksi.store');