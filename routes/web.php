<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RegionController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Province Route Access
Route::post('/province/get-province-by-select2', [ProvinceController::class, 'getProvinceBySelect2'])->name('get-province-by-select2');

Route::get('/province/export-pdf', [ProvinceController::class, 'exportPdf'])->name('province.export-pdf');

Route::get('/province/delete/{id}', [ProvinceController::class, 'destroy']);
Route::resource("province", ProvinceController::class);

// Region Route Access
Route::post('/region/get-region-by-select2', [RegionController::class, 'getRegionBySelect2'])->name('get-region-by-select2');
Route::get('/region/export-pdf', [RegionController::class, 'exportPdf'])->name('region.export-pdf');
Route::get('/region/delete/{id}', [RegionController::class, 'destroy']);
Route::resource("region", RegionController::class);
