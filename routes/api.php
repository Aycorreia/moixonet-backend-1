<?php

use App\Http\Controllers\ActiveChannelsController;
use App\Http\Controllers\ChannelsController;
use App\Http\Controllers\MessagesController;
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
Route::get('/v1/channels/{channel}', [ChannelsController::class, 'show' ] );

Route::group(['middleware' => ['auth']], function () {
    Route::post('/v1/channels', [ChannelsController::class, 'store' ] );
//    Route::delete('/v1/channels/{id}', [ChannelsController::class, 'destroy' ] );
    // ROUTE MODEL BINDING
    Route::delete('/v1/channels/{channel}', [ChannelsController::class, 'destroy' ] );
    Route::put('/v1/channels/{channel}', [ChannelsController::class, 'update' ] );

    Route::post('/v1/active_channels/{channel}', [ActiveChannelsController::class, 'store' ]);
    Route::delete('/v1/active_channels/{channel}', [ActiveChannelsController::class, 'destroy' ]);

});

Route::get('/v1/messages', [MessagesController::class, 'index' ] );
Route::get('/v1/messages/{id}', [MessagesController::class, 'index' ] );





// CRUDDY BY DESIGN: https://github.com/adamwathan/laracon2017
// https://www.youtube.com/watch?v=MF0jFKvS4SI

// USERS? PÚBLICA / INTRANET
  // ROLES -> Diferent tipus usuaris (agrupacions de permissos) ROLS TÍPICS
  // MANAGER-> ADMIN (administradors o superadministradors) -> 200
  // REGULAR USERS -> 403 -> REGULAR USER en alguns casos també 200
  // GUEST USER -> 401

// LOGGED/REGULAR USERS

// TODO -> Deures
// GET /api/v1/user/channels -> TORNI ELS CANALS DEL USUARI LOGAT
// POST /api/v1/user/channels
// UPDATE /api/v1/user/channels/{channel}
// DELETE /api/v1/user/channels/{channel}

// NESTED UserChannelsController -> UserChannelsControllerTest

// ACTIVE CHANNELS -> Estat estats objecte
// AIXÒ NO:
//DESACTIVAR -> PUT /api/v1/channels/{channel} [ 'active': false, name: 'MATEIX NAME']
//ACTIVAR -> PUT /api/v1/channels/{channel} [ 'active': true, name: 'MATEIX NAME']

// NOU CONCEPTE ActiveChannels -> ActiveChannelsController

//POST ActiveChannelsController -> activate() -> store()
//DELETE ActiveChannelsController -> activate() -> destroy()

//POST /api/v1/active_channels/{channel} post()
//DELETE /api/v1/active_channels/{channel}  destroy()

