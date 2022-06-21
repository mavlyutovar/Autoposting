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

use App\Http\Controllers\ThemeController;

Route::get('/', "ThemeController@index")->name('index');
Route::get('/show-text', "ThemeController@showText")->name('show-text');
Route::post('/update-text-theme', "ThemeController@update")->name('update-text-theme');
Route::post('/add-text-theme', "ThemeController@addText")->name('add-text-theme');
Route::post('/get-theme', "ThemeController@getThemeModel")->name('get-theme');
Route::post('/get-all-theme', "ThemeController@getAllTheme")->name('get-all-theme');
Route::post('/delete-theme', "ThemeController@deleteTheme")->name('delete-theme');


Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
