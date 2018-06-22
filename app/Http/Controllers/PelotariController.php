<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Pelotari;

class PelotariController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $pelotaris = DB::table('pelotaris')
          ->leftJoin('provincias', 'pelotaris.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'pelotaris.municipio_id', '=', 'municipios.id')
          ->select('pelotaris.*', 'provincias.name as provincia', 'municipios.name as municipio')
          ->orderBy('alias')
          ->get();

        return response()->json($pelotaris, 200);
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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $item = new Pelotari([
          'DNI' => $request->get('dni'),
          'nombre' => $request->get('nombre'),
          'apellidos' => $request->get('apellidos'),
          'alias' => $request->get('alias'),
          'posicion' => $request->get('posicion'),
          'direccion' => $request->get('direccion'),
          'cod_postal' => $request->get('cod_postal'),
          'municipio_id' => $request->get('municipio_id'),
          'provincia_id' => $request->get('provincia_id'),
          'email' => $request->get('email'),
          'telefono' => $request->get('telefono'),
        ]);

        $item->save();

        return response()->json($item, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $pelotari = DB::table('pelotaris')
          ->leftJoin('provincias', 'pelotaris.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'pelotaris.municipio_id', '=', 'municipios.id')
          ->select('pelotaris.*', 'provincias.name as provincia', 'municipios.name as municipio')
          ->where('pelotaris.id', $id)
          ->first();

        return response()->json($pelotari, 200);
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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $pelotari = Pelotari::find($id);

        $pelotari->DNI = $request->get('dni');
        $pelotari->nombre = $request->get('nombre');
        $pelotari->apellidos = $request->get('apellidos');
        $pelotari->alias = $request->get('alias');
        $pelotari->posicion = $request->get('posicion');
        $pelotari->direccion = $request->get('direccion');
        $pelotari->cod_postal = $request->get('cod_postal');
        $pelotari->municipio_id = $request->get('municipio_id');
        $pelotari->provincia_id = $request->get('provincia_id');
        $pelotari->email = $request->get('email');
        $pelotari->telefono = $request->get('telefono');

        $pelotari->save();

        return response()->json($pelotari, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        Pelotari::destroy($id);

        return response()->json("LARAVEL REMOVED", 200);
    }
}
