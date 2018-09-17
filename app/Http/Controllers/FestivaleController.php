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
              'provincias.id as provincia_id',
              'provincias.name as provincia',
              'municipios.id as municipio_id',
              'municipios.name as municipio',
              'frontones.name as fronton',
              'estado_festivals.name as estado'
            );

          if( null !== $request->get('filter') ) {
            $in_clause = array();

            $items = $items->leftJoin('festival_facturacion', 'festivales.id', '=', 'festival_facturacion.festival_id')
                           ->leftJoin('festival_partidos', 'festivales.id', '=', 'festival_partidos.festival_id')
                           ->leftJoin('festival_partido_pelotaris', 'festival_partidos.id', '=', 'festival_partido_pelotaris.festival_partido_id')
                           ->leftJoin('pelotaris', 'festival_partido_pelotaris.pelotari_id', '=', 'pelotaris.id')
                           ->groupBy('festivales.id');

            $filters = $request->get('filter');

            foreach($filters as $filter) {
              $filter = json_decode($filter);
              if( 'in' == $filter->operator ) {
                $in_clause[$filter->column][] = $filter->value;
              } else {
                $items = $items->where($filter->column, $filter->operator, $filter->value);
              }
            }

            if(count($in_clause)) {
              foreach($in_clause as $key => $clause) {
                $items = $items->whereIn($key, $clause);
              }
            }
          }

          $items = $items->where('festivales.deleted_at', null)
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
          ->select(
              'festivales.*',
              'provincias.id as provincia_id',
              'provincias.name as provincia',
              'municipios.id as municipio_id',
              'municipios.name as municipio',
              'frontones.name as fronton'
            )
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
        $request->user()->authorizeRoles(['admin', 'gerente', 'intendente']);

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
