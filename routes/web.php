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
Route::prefix('produk')->group(function () {
    Route::get('/','ProductController@productView');
    Route::get('tambah','ProductController@productInput');
    Route::post('tambah','ProductController@productStore');
    Route::get('cari','ProductController@productSearch');
    Route::get('cari_list','ProductController@productSearchList');
    Route::get('filter','ProductController@productFilter');
    Route::get('ubah/{productId}', 'ProductController@productEdit');
    Route::post('ubah/{productId}', 'ProductController@productUpdate');
    Route::post('hapus/{productId}', 'ProductController@productDelete');
});
Route::prefix('transaksi')->group(function () {
    Route::get('/','TransactionController@transactionView');
    Route::get('tambah','TransactionController@transactionInput');
    Route::post('tambah','TransactionController@transactionStore');
    Route::get('cari','TransactionController@transactionSearch');
    Route::get('cari_list','TransactionController@transactionSearchList');
    Route::get('filter','TransactionController@transactionFilter');
    Route::get('sort_by','TransactionController@transactionSortBy');
    Route::get('sort_by_date','TransactionController@transactionSortByDate');
    Route::get('sort_by_date_sum','TransactionController@transactionSortByDateSum');
    Route::get('detail/{transactionId}', 'TransactionController@transactionDetail');
    Route::get('ubah/{transactionId}', 'TransactionController@transactionEdit');
    Route::post('ubah/{transactionId}', 'TransactionController@transactionUpdate');
    Route::post('hapus/{transactionId}', 'TransactionController@transactionDelete');
    Route::post('export_excel', 'TransactionController@exportExcel');
    Route::get('export_pdf', 'TransactionController@ExportPDF');
});
Route::prefix('hutang')->group(function () {
    Route::get('/','AccountPaylableController@debtView');
    Route::get('tambah','AccountPaylableController@debtInput');
    Route::post('tambah','AccountPaylableController@debtStore');
    Route::get('cari','AccountPaylableController@debtSearch');
    Route::get('sort_by','AccountPaylableController@debtSortBy');
    Route::get('sort_by_date','AccountPaylableController@debtSortByDate');
    Route::get('detail/{apId}', 'AccountPaylableController@debtDetail');
    Route::post('tandai_lunas/{apId}', 'AccountPaylableController@debtTandaiLunas');
    Route::post('atur_tanggal/{apId}', 'AccountPaylableController@debtAturTanggal');
    Route::post('bayar/{apId}', 'AccountPaylableController@debtPay');
    // Route::post('export_excel', 'AccountPaylableController@exportExcel');
    Route::get('export_pdf', 'AccountPaylableController@ExportPDF');
});
