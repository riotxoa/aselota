<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\FestivalCoste;
use App\FestivalCosteEntradas;

class FestivalCosteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $id = $request->get('festival_id');

        $costes = FestivalCoste::where('festival_id', $id)->get();

        foreach( $costes as $key => $coste ) {
          $entradas = FestivalCosteEntradas::where('festival_id', $id)->orderBy('id')->get();
          $costes[$key]->entradas = $entradas;
        }

        return response()->json($costes, 200);
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

        $costes = FestivalCoste::where('festival_id', $request->get('festival_id'))->first();

        if($costes)
          return $this->update($request, $costes->id);
        
        $costes = new FestivalCoste([
          'festival_id' => $request->get('festival_id'),
          'coste_pelotaris' => $request->get('coste_pelotaris'),
          'coste_jueces' => $request->get('coste_jueces'),
          'coste_cancha' => $request->get('coste_cancha'),
          'coste_material' => $request->get('coste_material'),
          'coste_auxiliares' => $request->get('coste_auxiliares'),
          'coste_taquillera' => $request->get('coste_taquillera'),
          'coste_sanidad' => $request->get('coste_sanidad'),
          'coste_empresa' => $request->get('coste_empresa'),
          'sanidad' => $request->get('sanidad'),
          'num_auxiliares' => $request->get('num_auxiliares'),
          'num_taquilleros' => $request->get('num_taquilleros'),
          'importe_venta' => $request->get('importe_venta'),
          'aportacion' => $request->get('aportacion'),
          'num_espectadores' => $request->get('num_espectadores'),
          'ingreso_taquilla' => $request->get('ingreso_taquilla'),
          'ingreso_ayto' => $request->get('ingreso_ayto'),
          'ingreso_otros' => $request->get('ingreso_otros'),
          'cliente_id' => $request->get('cliente_id'),
          'cliente_txt' => $request->get('cliente_txt'),
          'porcentaje' => $request->get('porcentaje'),
        ]);

        $costes->save();

        return response()->json($costes, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $costes = FestivalCoste::find($id);

        return response()->json($costes, 200);
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

        $costes = FestivalCoste::find($id);

        $costes->coste_pelotaris = $request->get('coste_pelotaris');
        $costes->coste_jueces = $request->get('coste_jueces');
        $costes->coste_cancha = $request->get('coste_cancha');
        $costes->coste_material = $request->get('coste_material');
        $costes->coste_auxiliares = $request->get('coste_auxiliares');
        $costes->coste_taquillera = $request->get('coste_taquillera');
        $costes->coste_sanidad = $request->get('coste_sanidad');
        $costes->coste_empresa = $request->get('coste_empresa');
        $costes->sanidad = $request->get('sanidad');
        $costes->num_auxiliares = $request->get('num_auxiliares');
        $costes->num_taquilleros = $request->get('num_taquilleros');
        $costes->importe_venta = $request->get('importe_venta');
        $costes->aportacion = $request->get('aportacion');
        $costes->num_espectadores = $request->get('num_espectadores');
        $costes->ingreso_taquilla = $request->get('ingreso_taquilla');
        $costes->ingreso_ayto = $request->get('ingreso_ayto');
        $costes->ingreso_otros = $request->get('ingreso_otros');
        $costes->cliente_id = $request->get('cliente_id');
        $costes->cliente_txt = $request->get('cliente_txt');
        $costes->porcentaje = $request->get('porcentaje');

        $costes->save();

        return response()->json($costes, 200);
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

        FestivalCoste::destroy($id);

        return response()->json("COSTES REMOVED", 200);
    }
}
