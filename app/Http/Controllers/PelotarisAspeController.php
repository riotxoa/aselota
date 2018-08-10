<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PelotarisAspe;

class PelotarisAspeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'gerente']);

      $items = \App\PelotarisAspe::orderBy('alias', 'asc')->get();

      $items = DB::table('pelotaris_aspe')
        ->select('pelotaris_aspe.*')
        ->where('pelotaris_aspe.deleted_at', null);

      if($request->get('fecha')) {
        $fecha = $request->get('fecha');
        $items = $items->whereDate('pelotaris_aspe.fecha_ini', '<=', $fecha)
                       ->where( function ($query) use ($fecha) {
                         $query->where('pelotaris_aspe.fecha_fin', '>=', $fecha)
                               ->orWhere('pelotaris_aspe.fecha_fin', null);
                       });
                       //whereDate('pelotaris_aspe.fecha_fin', '>=', $fecha);
      }

      $items = $items->orderBy('alias')
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
    public function destroy($id)
    {
        //
    }
}
