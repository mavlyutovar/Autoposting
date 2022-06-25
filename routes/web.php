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

Route::get('/', "PageController@index")->name('index');
Route::get('/home', "PageController@index")->name('index');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/posts', "PostTimeController@index")->name('index');
    Route::post('/add-post', "PostTimeController@addNewPostTime")->name('add-post');
    Route::post('/show-all-post', "PostTimeController@showAllPostTime")->name('show-all-post');
    Route::post('/delete-post/{id}', "PostTimeController@deletePostTime")->name('delete-post');

    /*              РОУТЫ ГРУПП */
    Route::get('/groups', "GroupController@index")->name('index');
    Route::post('/add-group', "GroupController@addNewGroup")->name('add-group');
    Route::post('/show-all-group', "GroupController@showAllGroup")->name('show-all-group');
    Route::post('/delete-group/{id}', "GroupController@deleteGroup")->name('delete-group');

    /*              РОУТЫ ТЕМ */
    Route::get('/themes', "ThemeController@index")->name('index');

    Route::post('/duplicate-theme/{id}', "ThemeController@duplicateThemeModel")->name('duplicate-theme');
    Route::post('/show-theme/{id}', "ThemeController@showThemeModel")->name('show-theme');
    Route::post('/delete-theme/{id}', "ThemeController@deleteTheme")->name('delete-theme');
    Route::post('/edit-theme/{id}', "ThemeController@setEditTheme")->name('edit-theme');
    Route::post('/undo-edit-theme', "ThemeController@undoEditTheme")->name('undo-edit-theme');
    Route::post('/get-all-theme', "ThemeController@getAllTheme")->name('get-all-theme');


    Route::post('/get-theme', "ThemeController@getThemeModel")->name('get-theme');
    Route::post('/how-ready', "ThemeController@getPercentReadyTheme")->name('how-ready');
    Route::post('/set-name-theme', "ThemeController@setThemeName")->name('set-name-theme');

    Route::get('/show-pic-theme', "ThemeController@showPicture")->name('show-pic');
    Route::post('/add-pic-theme', "ThemeController@addPicture")->name('add-pic-theme');
    Route::post('/update-pic-theme', "ThemeController@updatePicture")->name('update-pic-theme');

    Route::get('/show-text-theme', "ThemeController@showText")->name('show-text-theme');
    Route::post('/update-text-theme', "ThemeController@updateText")->name('update-text-theme');
    Route::post('/add-text-theme', "ThemeController@addText")->name('add-text-theme');

    Route::get('/show-audio-theme', "ThemeController@showAudio")->name('show-audio-theme');
    Route::post('/update-audio-theme', "ThemeController@updateAudio")->name('update-audio-theme');
    Route::post('/add-audio-theme', "ThemeController@addAudio")->name('add-audio-theme');

    Route::post('/get-setting-theme', "ThemeController@getSettingTheme")->name('get-setting-theme');
    Route::post('/set-setting-theme', "ThemeController@setSettingTheme")->name('set-setting-theme');
});



Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
