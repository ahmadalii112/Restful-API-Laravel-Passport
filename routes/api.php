<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Country\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('country', [CountryController::class, 'country'])->name('country.all'); // All Records
Route::get('country/{country}', [CountryController::class, 'showCountryByID'])->name('country.show');// Show Record by ID
// To add a record
Route::post('country', [CountryController::class, 'storeCountryRecord'])->name('country.store');
// TO update record
Route::put('country/{country}', [CountryController::class, 'updateCountryRecord'])->name('country.update');
// TO delete record
Route::delete('country/{country}', [CountryController::class, 'deleteCountryRecord'])->name('country.delete');

Route::apiResource('countryResource', \App\Http\Controllers\Country\CountryResourceController::class)->middleware('client');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
