<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\apiAuthMiddl;

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

Route::get('/', function () {
    return view('welcome');
});
//routas de usuario
Route::post('/api/register', 'App\Http\Controllers\UsersController@register');
Route::post('/api/login', 'App\Http\Controllers\UsersController@login');
Route::put('/api/user/update', 'App\Http\Controllers\UsersController@update');
Route::post('/api/user/upload', 'App\Http\Controllers\UsersController@upload')->middleware(apiAuthMiddl::class);
Route::get('/api/user/normal', 'App\Http\Controllers\UsersController@getAllMonitores');

route::get('/api/user/avatar/{filename}', 'App\Http\Controllers\UsersController@getImage');
route::get('/api/user/detaill/{id}', 'App\Http\Controllers\UsersController@detail');
Route::resource('/api/lugar', 'App\Http\Controllers\LugarController');