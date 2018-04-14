<?php

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

Route::post('/authenticate', 'Auth\LoginController@authenticate');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pdf-to-image', 'PdfToImageController');