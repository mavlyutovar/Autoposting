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

Route::get('/', "GroupController@index")->name('index');
Route::get('/create-theme', "GroupController@index")->name('create-theme');


Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
