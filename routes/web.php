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
    Route::resource('pelotaris-aspe', 'PelotarisAspeController');
    Route::resource('pelotaris-profesional', 'PelotarisProfesionalController');
    Route::resource('pelotaris-promesa', 'PelotarisPromesaController');
    Route::resource('pelotaris-cuadro', 'PelotarisCuadroController');
    Route::resource('contratos/tramo', 'ContratoController');
    Route::resource('contratos/comercial', 'ContratoComercialController');
    Route::resource('contratos/header', 'ContratoHeaderController');
    Route::resource('contratos', 'ContratoController');
    Route::resource('tarifas', 'ContratoCampeonatoController');
    Route::resource('derechos', 'DerechoController');
    Route::resource('frontones', 'FrontonController');
    Route::resource('festival-estados', 'EstadoFestivalController');
    Route::resource('festivales', 'FestivaleController');
    Route::resource('tipos-partido', 'TipoPartidoController');
    Route::resource('partidos', 'FestivalPartidoController');
    Route::resource('clientes', 'ClienteController');
    Route::resource('festival-costes', 'FestivalCosteController');
    Route::resource('costes-fijos', 'CostesFijosController');
    Route::resource('festival-facturacion', 'FestivalFacturacionController');
    Route::resource('festival-entradas', 'FestivalCosteEntradasController');
    Route::resource('festival-contactos', 'FestivalContactoController');
    Route::resource('explotaciones', 'ExplotacionController');
    Route::resource('formas-pago', 'FormasPagoController');
    Route::resource('envio-facturas', 'FacturaEnvioController');
    Route::resource('entrenamientos/contenidos', 'EntrContenidoController');
    Route::resource('entrenamientos/actitudes', 'EntrActitudController');
    Route::resource('entrenamientos/aprovechamientos', 'EntrAprovechamientoController');
    Route::resource('entrenamientos/evoluciones', 'EntrEvolucionController');
    Route::resource('entrenamientos/frontones', 'EntrFrontonController');
    Route::resource('entrenamientos', 'EntrenamientoController');
    Route::resource('eventos/motivos', 'PrensaMotivoController');
    Route::resource('eventos', 'PrensaEventoController');
    Route::resource('partes', 'ParteMedicoController');

    Route::get('calendario', 'FestivaleController@getCalendarMonth');
    Route::get('entrenamiento', 'EntrenamientoController@getEntrenamiento');

    Route::post('users/{id}/update', 'UserController@update');
    Route::post('pelotaris/{id}/update', 'PelotariController@update');
    Route::post('contratos/tramo/{id}/update', 'ContratoController@update');
    Route::post('contratos/comercial/{id}/update', 'ContratoComercialController@update');
    Route::get('contratos/header/{id}/download', 'ContratoHeaderController@download_contrato');
    Route::get('contratos/header/{id}/derechos/download', 'ContratoHeaderController@download_contrato_derechos');
    Route::post('contratos/header/{id}/update', 'ContratoHeaderController@update');
    Route::post('tarifas/{id}/update', 'ContratoCampeonatoController@update');
    Route::post('derechos/{id}/update', 'DerechoController@update');
    Route::post('festivales/{id}/update', 'FestivaleController@update');
    Route::post('festival-entradas/{id}/update', 'FestivalCosteEntradasController@update' );
    Route::get('festival-facturacion/{id}/download', 'FestivalFacturacionController@download_factura');
    Route::post('partidos/{id}/update', 'FestivalPartidoController@update');
    Route::post('partido-pelotaris/{id}/update', 'FestivalPartidoController@update_pelotari');
    Route::post('partido-celebrado/{id}/update', 'FestivalPartidoController@update_partido_celebrado');
    Route::post('entrenamientos/{id}/update', 'EntrenamientoController@update');
    Route::get('eventos/pelotaris/{id}', 'PrensaEventoController@get_pelotaris');
    Route::post('eventos/{id}/add/pelotari', 'PrensaEventoController@add_pelotari');
    Route::post('eventos/{id}/update/pelotari', 'PrensaEventoController@update_pelotari');
    Route::post('eventos/{id}/delete/pelotari', 'PrensaEventoController@delete_pelotari');
    Route::post('eventos/{id}/update', 'PrensaEventoController@update');
    Route::get('partes/{id}/get', 'ParteMedicoController@getParte');
    Route::post('partes/{id}/update', 'ParteMedicoController@update');

    Route::get('partes-medicos-aux', 'ParteMedicoController@getAuxTablesInfo');
});

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/user', 'UserController@edit');

Route::post('envio-confirmacion-email','MailController@html_email');
Route::get('exportar-cuadro-mando','CuadroMandoController@export');
