<?php

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

// Route::get('pelotaris', 'API\v1\PelotariController@index');
// Route::get('pelotaris-aspe', 'API\v1\PelotariAspeController@index');
// Route::get('frontones', 'API\v1\FrontonController@index');
// Route::get('campeonatos', 'API\v1\CampeonatoController@index');
