<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Med2AuxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
    }

    public function getAuxTablesInfo(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $response = new \stdClass;

      $response->causantes = \App\Med2Causante::all();
      $response->centros = \App\Med2Centro::all();
      $response->grados_lesion = \App\Med2GradoLesion::all();
      $response->lugares = \App\Med2Lugar::all();
      $response->medicos = \App\Med2Medico::all();
      $response->pruebas = \App\Med2Prueba::all();
      $response->tiempos_trabajo = \App\Med2TiempoTrabajo::all();
      $response->tipos_asistencia = \App\Med2TipoAsistencia::all();

      return response()->json($response, 200);
    }



}
