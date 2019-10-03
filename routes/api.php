<?php

use App\Http\Controllers\ChannelsController;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/v1/channels', 'ChannelsController@index');
Route::get('/v1/channels', [ChannelsController::class, 'index' ] );



// GET /channels index()
// GET /channels/{id} show()
// POST /channels store()
// PUT/PATCH /channels/{id} update()
// DELETE /channels/{id} destroy()
