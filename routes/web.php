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

    Route::get('nominas', 'PelotariController@getNominas');
    Route::post('nominas/{id}/update', 'PelotariController@updateNomina');

    Route::group(['prefix' => 'med2'], function () {
      Route::get('aux', 'Med2AuxController@getAuxTablesInfo');
      Route::get('pelotaris', 'Med2PartesController@getPartesProfesionales');
      Route::get('promesas', 'Med2PartesController@getPartesPromesas');

      /* Partes de Accidente */
      Route::get('p_accidente', 'Med2PartesAccidenteController@getAll');
      Route::post('p_accidente', 'Med2PartesAccidenteController@store');
      Route::post('p_accidente/{id}/remove', 'Med2PartesAccidenteController@destroy');
      Route::post('p_accidente/{id}/update', 'Med2PartesAccidenteController@update');

      Route::post('p_accidente/upload-informe/{parte_id}', 'Med2PartesAccidenteController@uploadInforme');
      Route::post('p_accidente/update-informe/{parte_id}', 'Med2PartesAccidenteController@updateInforme');
      Route::get('p_accidente/{parte_id}/informes', 'Med2PartesAccidenteController@getInformes');
      Route::delete('p_accidente/informe/{id}', 'Med2PartesAccidenteController@deleteInforme');
      Route::get('p_accidente/informe/{id}/download', 'Med2PartesAccidenteController@downloadInforme');

      /* Partes de Enfermedad */
      Route::get('p_enfermedad', 'Med2PartesEnfermedadController@getAll');
      Route::post('p_enfermedad', 'Med2PartesEnfermedadController@store');
      Route::post('p_enfermedad/{id}/remove', 'Med2PartesEnfermedadController@destroy');
      Route::post('p_enfermedad/{id}/update', 'Med2PartesEnfermedadController@update');

      Route::post('p_enfermedad/upload-informe/{parte_id}', 'Med2PartesEnfermedadController@uploadInforme');
      Route::post('p_enfermedad/update-informe/{parte_id}', 'Med2PartesEnfermedadController@updateInforme');
      Route::get('p_enfermedad/{parte_id}/informes', 'Med2PartesEnfermedadController@getInformes');
      Route::delete('p_enfermedad/informe/{id}', 'Med2PartesEnfermedadController@deleteInforme');
      Route::get('p_enfermedad/informe/{id}/download', 'Med2PartesEnfermedadController@downloadInforme');

      /* Partes de Fisiología */
      Route::get('p_fisiologia', 'Med2PartesFisiologiaController@getAll');
      Route::post('p_fisiologia', 'Med2PartesFisiologiaController@store');
      Route::post('p_fisiologia/{id}/remove', 'Med2PartesFisiologiaController@destroy');
      Route::post('p_fisiologia/{id}/update', 'Med2PartesFisiologiaController@update');

      Route::post('p_fisiologia/upload-informe/{parte_id}', 'Med2PartesFisiologiaController@uploadInforme');
      Route::post('p_fisiologia/update-informe/{parte_id}', 'Med2PartesFisiologiaController@updateInforme');
      Route::get('p_fisiologia/{parte_id}/informes', 'Med2PartesFisiologiaController@getInformes');
      Route::delete('p_fisiologia/informe/{id}', 'Med2PartesFisiologiaController@deleteInforme');
      Route::get('p_fisiologia/informe/{id}/download', 'Med2PartesFisiologiaController@downloadInforme');

      /* Partes de Prevención */
      Route::get('p_prevencion', 'Med2PartesPrevencionController@getAll');
      Route::post('p_prevencion', 'Med2PartesPrevencionController@store');
      Route::post('p_prevencion/{id}/remove', 'Med2PartesPrevencionController@destroy');
      Route::post('p_prevencion/{id}/update', 'Med2PartesPrevencionController@update');

      Route::post('p_prevencion/upload-informe/{parte_id}', 'Med2PartesPrevencionController@uploadInforme');
      Route::post('p_prevencion/update-informe/{parte_id}', 'Med2PartesPrevencionController@updateInforme');
      Route::get('p_prevencion/{parte_id}/informes', 'Med2PartesPrevencionController@getInformes');
      Route::delete('p_prevencion/informe/{id}', 'Med2PartesPrevencionController@deleteInforme');
      Route::get('p_prevencion/informe/{id}/download', 'Med2PartesPrevencionController@downloadInforme');

      /* Notificaciones */
      Route::get('notificaciones', 'Med2PartesController@getNotifications');
      Route::get('notificaciones/{pelotari_id}', 'Med2PartesController@getNotifications');
      Route::post('notificacion', 'Med2PartesController@sendNotification');

      Route::get('user/notificaciones', 'Med2PartesController@getNotificationsByUserID');
      Route::post('notificacion/{notificacion_id}/read', 'Med2PartesController@readNotification');
      Route::post('notificacion/{notificacion_id}/unread', 'Med2PartesController@unreadNotification');
    });

    Route::group(['prefix' => 'PLEN'], function () {
      Route::resource('ejercicios', 'PLEN_EjercicioController');
      Route::resource('fases-sesion', 'PLEN_FaseSesionController');
      Route::resource('tipos-ejercicio', 'PLEN_TipoEjercicioController');
      Route::resource('tipos-mesociclo', 'PLEN_TipoMesocicloController');
      Route::resource('tipos-microciclo', 'PLEN_TipoMicrocicloController');
      Route::resource('subtipos-ejercicio', 'PLEN_SubtipoEjercicioController');
    });
});

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/user', 'UserController@edit');

Route::post('envio-confirmacion-email','MailController@html_email');
Route::get('exportar-cuadro-mando','CuadroMandoController@export');
