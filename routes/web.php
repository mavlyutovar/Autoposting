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

Route::post('/how-ready', "ThemeController@getPercentReadyTheme")->name('how-ready');

Route::get('/show-pic-theme', "ThemeController@showPicture")->name('show-pic');
Route::post('/add-pic-theme', "ThemeController@addPicture")->name('add-pic-theme');
Route::post('/update-pic-theme', "ThemeController@updatePicture")->name('update-pic-theme');

Route::get('/show-text-theme', "ThemeController@showText")->name('show-text-theme');
Route::post('/update-text-theme', "ThemeController@updateText")->name('update-text-theme');
Route::post('/add-text-theme', "ThemeController@addText")->name('add-text-theme');

Route::get('/show-audio-theme', "ThemeController@showAudio")->name('show-audio-theme');
Route::post('/update-audio-theme', "ThemeController@updateAudio")->name('update-audio-theme');
Route::post('/add-audio-theme', "ThemeController@addAudio")->name('add-audio-theme');

Route::post('/get-theme', "ThemeController@getThemeModel")->name('get-theme');
Route::post('/get-all-theme', "ThemeController@getAllTheme")->name('get-all-theme');
Route::post('/delete-theme', "ThemeController@deleteTheme")->name('delete-theme');
Route::post('/set-name-theme', "ThemeController@setThemeName")->name('set-name-theme');
Route::post('/get-setting-theme', "ThemeController@getSettingTheme")->name('get-setting-theme');
Route::post('/set-setting-theme', "ThemeController@setSettingTheme")->name('set-setting-theme');



Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
