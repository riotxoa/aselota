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

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('login', ['uses' => 'HomeController@index', 'as' => 'voyager.login']);

});

Route::group(['prefix' => 'www'], function () {
    Route::resource('provincias', 'ProvinciaController');
    Route::resource('municipios', 'MunicipioController');
    Route::resource('campeonatos', 'CampeonatoController');
    Route::resource('pelotaris', 'PelotariController');
    Route::resource('contratos', 'ContratoController');
    Route::resource('tarifas', 'ContratoCampeonatoController');
    Route::resource('derechos', 'DerechoController');
    Route::resource('frontones', 'FrontonController');
    Route::resource('festival-estados', 'EstadoFestivalController');
    Route::resource('festivales', 'FestivaleController');
    Route::resource('tipos-partido', 'TipoPartidoController');
    Route::resource('partidos', 'FestivalPartidoController');

    Route::post('users/{id}/update', 'UserController@update');
    Route::post('pelotaris/{id}/update', 'PelotariController@update');
    Route::post('contratos/{id}/update', 'ContratoController@update');
    Route::post('tarifas/{id}/update', 'ContratoCampeonatoController@update');
    Route::post('derechos/{id}/update', 'DerechoController@update');
    Route::post('festivales/{id}/update', 'FestivaleController@update');
    Route::post('partidos/{id}/update', 'FestivalPartidoController@update');
});

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/user', 'UserController@edit');
