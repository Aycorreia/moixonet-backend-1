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

use App\Channel;

Route::get('/', function () {
    return view('welcome');
});
//
//
//Route::get('/crear_channels', function () {
//    Channel::create([
//        'name' => 'Canal 1'
//    ]);
//
//    Channel::create([
//        'name' => 'Canal 2'
//    ]);
//
//    Channel::create([
//        'name' => 'Canal 3'
//    ]);
//});
