<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contrato;
use App\ContratoHeader;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $id = $request->get('pelotari_id');

        $items = \App\ContratoHeader::where('pelotari_id', $id)->where('deleted_at', null)->orderBy('fecha_fin', 'desc')->get();

        foreach($items as $key => $item) {
          $tramo = \App\Contrato::where('header_id', $item->id)->orderBy('fecha_fin', 'desc')->get();
          $comercial = \App\ContratoComercial::where('header_id', $item->id)->orderBy('fecha_fin', 'desc')->get();
          $item->tramos = $tramo;/*
            //CAMBIO1
            //Aquí hacemos el cálculo con la fórmula y los datos del tramo del jugador.
          foreach($tramo as $key => $t) {
              //$c->coste = 3;
              foreach($comercial as $key => $c){
                if($c->header_id == $t->header_id){
                    $c->coste = $t->dieta_mes;
                }
              }
          }*/
          $item->comerciales = $comercial;
        }

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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $item = new Contrato([
          'header_id' => $request->get('header_id'),
          'pelotari_id' => $request->get('pelotari_id'),
          'fecha_ini' => $request->get('fecha_ini'),
          'fecha_fin' => $request->get('fecha_fin'),
          'ficha' => $request->get('ficha'),
          'sueldo' => $request->get('sueldo'),
          'dieta_mes' => ($request->get('dieta_mes') ? $request->get('dieta_mes') : 0),
          'dieta_partido' => ($request->get('dieta_partido') ? $request->get('dieta_partido') : 0),
          'prima_partido' => ($request->get('prima_partido') ? $request->get('prima_partido') : 0),
          'prima_partido_no_gar' => ($request->get('prima_partido_no_gar') ? $request->get('prima_partido_no_gar') : 0),
          'prima_estelar' => ($request->get('prima_estelar') ? $request->get('prima_estelar') : 0),
          'prima_manomanista' => ($request->get('prima_manomanista') ? $request->get('prima_manomanista') : 0),
          'prima_manomanista_promo' => ($request->get('prima_manomanista_promo') ? $request->get('prima_manomanista_promo') : 0),
          'garantia' => ($request->get('garantia') ? $request->get('garantia') : 0),
          'formacion' => ($request->get('formacion') ? $request->get('formacion') : 0),
          'd_imagen' => ($request->get('d_imagen') ? $request->get('d_imagen') : 0),
        ]);

        $item->save();

        return response()->json($request, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $item = Contrato::find($id);

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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $item = Contrato::find($id);

        $item->fecha_ini = $request->get('fecha_ini');
        $item->fecha_fin = $request->get('fecha_fin');
        $item->ficha = $request->get('ficha');
        $item->sueldo = $request->get('sueldo');
        $item->dieta_mes = $request->get('dieta_mes');
        $item->dieta_partido = $request->get('dieta_partido');
        $item->prima_partido = $request->get('prima_partido');
        $item->prima_partido_no_gar = $request->get('prima_partido_no_gar');
        $item->prima_estelar = $request->get('prima_estelar');
        $item->prima_manomanista = $request->get('prima_manomanista');
        $item->prima_manomanista_promo = $request->get('prima_manomanista_promo');
        $item->garantia = $request->get('garantia');
        $item->formacion = $request->get('formacion');
        $item->d_imagen = $request->get('d_imagen');

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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        Contrato::destroy($id);

        return response()->json("TRAMO REMOVED", 200);
    }
}
