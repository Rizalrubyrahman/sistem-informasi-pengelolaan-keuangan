<?php

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


Route::get('/','AuthController@viewLogin')->name('login');
Route::post('/login','AuthController@login');
Route::post('/logout','AuthController@logout');

Route::get('/dashboard', 'DashboardController@dashboard');
Route::prefix('stok_barang')->group(function () {
    Route::get('/','ProductController@productView');
    Route::get('tambah','ProductController@productInput');
    Route::post('tambah','ProductController@productStore');
    Route::get('cari','ProductController@productSearch');
    Route::get('filter','ProductController@productFilter');
    Route::get('ubah/{productId}', 'ProductController@productEdit');
    Route::post('ubah/{productId}', 'ProductController@productUpdate');
    Route::post('hapus/{productId}', 'ProductController@productDelete');
});
Route::prefix('transaksi')->group(function () {
    Route::get('/','ProductController@productView');
    Route::get('tambah','ProductController@productInput');
    Route::post('tambah','ProductController@productStore');
    Route::get('cari','ProductController@productSearch');
    Route::get('filter','ProductController@productFilter');
    Route::get('ubah/{productId}', 'ProductController@productEdit');
    Route::post('ubah/{productId}', 'ProductController@productUpdate');
    Route::post('hapus/{productId}', 'ProductController@productDelete');
});
