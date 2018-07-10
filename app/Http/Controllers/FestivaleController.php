<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Festivale;

class FestivaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $items = DB::table('festivales')
          ->leftJoin('frontones', 'festivales.fronton_id', '=', 'frontones.id')
          ->leftJoin('provincias', 'frontones.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'frontones.municipio_id', '=', 'municipios.id')
          ->leftJoin('estado_festivals', 'festivales.estado_id', '=', 'estado_festivals.id')
          ->select(
              'festivales.*',
              DB::raw('IF(festivales.television, "Sí", "No") AS television_txt'),
              'provincias.name as provincia',
              'municipios.name as municipio',
              'frontones.name as fronton',
              'estado_festivals.name as estado'
            )
          ->where('festivales.deleted_at', null)
          ->orderBy('festivales.fecha', 'desc')
          ->get();

        return response()->json($items, 200);
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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $item = new Festivale([
          'fecha' => $request->get('fecha'),
          'hora' => $request->get('hora'),
          'fronton_id' => $request->get('fronton_id'),
          'television' => $request->get('television'),
          'television_txt' => $request->get('television_txt'),
          'estado_id' => $request->get('estado_id'),
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

        $item = DB::table('festivales')
          ->leftJoin('frontones', 'festivales.fronton_id', '=', 'frontones.id')
          ->leftJoin('provincias', 'frontones.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'frontones.municipio_id', '=', 'municipios.id')
          ->select('festivales.*', 'provincias.name as provincia', 'municipios.name as municipio', 'frontones.name as fronton')
          ->where('festivales.id', $id)
          ->first();

        return response()->json($item, 200);
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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $item = Festivale::find($id);

        $item->fecha = $request->get('fecha');
        $item->hora = $request->get('hora');
        $item->fronton_id = $request->get('fronton_id');
        $item->television = $request->get('television');
        $item->television_txt = $request->get('television_txt');
        $item->estado_id = $request->get('estado_id');

        $item->save();

        return response()->json($item, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        Festivale::destroy($id);

        return response()->json("FESTIVAL REMOVED", 200);
    }
}
