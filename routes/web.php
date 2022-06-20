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

Route::get('/', "GroupController@index")->name('index');
Route::get('/show-text', "ThemeController@showText")->name('show-text');
Route::post('/update-text-theme', "ThemeController@update")->name('update-text-theme');
Route::post('/add-text-theme', "ThemeController@addText")->name('add-text-theme');


Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
