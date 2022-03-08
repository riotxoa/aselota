<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Support\Facades\DB;

class PelotariAspeController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $items = \App\PelotarisAspe::select(
            'pelotaris_aspe.id',
            'pelotaris_aspe.alias',
            'pelotaris_aspe.posicion'
          )
          ->where('pelotaris_aspe.deleted_at', null)
          ->orderBy('alias')
          ->get();

        return response()->json($items, 200);
    }
}
