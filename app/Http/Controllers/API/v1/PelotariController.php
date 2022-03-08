<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Support\Facades\DB;

class PelotariController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $select = array(
          'pelotaris.id',
          'pelotaris.alias',
          'pelotaris.nombre',
          'pelotaris.apellidos',
          'pelotaris.posicion',
          'pelotaris.fecha_nac as fecha_nacimiento',
          'provincias.name as provincia_nacimiento',
          'municipios.name as municipio_nacimiento'
        );

        $items = DB::table('pelotaris')
          ->leftJoin('provincias', 'pelotaris.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'pelotaris.municipio_id', '=', 'municipios.id')
          ->select($select)
          ->where('pelotaris.deleted_at', null)
          ->orderBy('alias')
          ->get();

        return response()->json($items, 200);
    }
}
