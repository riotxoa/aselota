<?php

namespace App\Http\Controllers\API\v1;

class FrontonController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $items = \App\Fronton::select(
            'frontones.id',
            'frontones.name',
            'frontones.direccion',
            'frontones.cod_postal',
            'municipios.name as municipio',
            'provincias.name as provincia'
          )
          ->leftJoin('municipios', 'municipios.id', 'frontones.municipio_id')
          ->leftJoin('provincias', 'provincias.id', 'frontones.provincia_id')
          ->orderBy('name', 'ASC')
          ->get();

        return response()->json($items, 200);
    }
}
