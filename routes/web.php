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
    Route::post('/send-post/{id}', "PostTimeController@sendPostTime")->name('send-post');

    /*              РОУТЫ ГРУПП */
    Route::get('/groups', "GroupController@index")->name('index');
    Route::post('/add-group', "GroupController@addNewGroup")->name('add-group');
    Route::post('/show-all-group', "GroupController@showAllGroup")->name('show-all-group');
    Route::post('/delete-group/{id}', "GroupController@deleteGroup")->name('delete-group');

    /*              РОУТЫ ТЕМ */
    Route::get('/themes', "ThemeController@index")->name('index');

    Route::post('/get-theme', "ThemeController@getThemeModel")->name('get-theme');
    Route::post('/how-ready', "ThemeController@getPercentReadyTheme")->name('how-ready');
    Route::post('/set-name-theme', "ThemeController@setThemeName")->name('set-name-theme');

    /*Роуты редактирования Медия */
    Route::get('/show-audio', "ThemeController@showAudio")->name('show-audio');
    Route::post('/save-audio', "ThemeController@saveAudio")->name('save-audio');
    Route::post('/del-audio/{id}', "ThemeController@delAudio")->name('del-audio');

    Route::get('/show-text', "ThemeController@showText")->name('show-text');
    Route::post('/save-text', "ThemeController@saveText")->name('save-text');
    Route::post('/del-text/{id}', "ThemeController@delText")->name('del-text');

    Route::get('/show-picture', "ThemeController@showPicture")->name('show-picture');
    Route::post('/save-picture', "ThemeController@savePicture")->name('save-picture');
    Route::post('/del-picture/{id}', "ThemeController@delPicture")->name('del-picture');

    Route::post('/get-setting-theme', "ThemeController@getSettingTheme")->name('get-setting-theme');
    Route::post('/set-setting-theme', "ThemeController@setSettingTheme")->name('set-setting-theme');
});



Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
