<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contrato;

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

        // $items = Contrato::where('pelotari_id', $id)->get();

        $items = DB::table('contratos')
          ->select(
            'contratos.*',
            DB::raw('DATE_FORMAT(contratos.fecha_ini, "%d-%m-%Y") AS fecha_inicio'),
            DB::raw('DATE_FORMAT(contratos.fecha_fin, "%d-%m-%Y") AS fecha_final'),
            DB::raw('CONCAT("Del ", DATE_FORMAT(contratos.fecha_ini, "%d-%m-%Y"), " al ", DATE_FORMAT(contratos.fecha_fin, "%d-%m-%Y")) AS periodo')
            )
          ->where('pelotari_id', $id)
          ->where('deleted_at', null)
          ->orderBy('fecha_ini', 'desc')
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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $item = new Contrato([
          'pelotari_id' => $request->get('pelotari_id'),
          'fecha_ini' => $request->get('fecha_ini'),
          'fecha_fin' => $request->get('fecha_fin'),
          'dieta_mes' => $request->get('dieta_mes'),
          'dieta_partido' => $request->get('dieta_partido'),
          'prima_partido' => $request->get('prima_partido'),
          'prima_estelar' => $request->get('prima_estelar'),
          'prima_manomanista' => $request->get('prima_manomanista'),
          'garantia' => $request->get('garantia'),
          'coste' => $request->get('coste'),
          'formacion' => $request->get('formacion'),
          'd_imagen' => $request->get('d_imagen'),
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
        $item->dieta_mes = $request->get('dieta_mes');
        $item->dieta_partido = $request->get('dieta_partido');
        $item->prima_partido = $request->get('prima_partido');
        $item->prima_estelar = $request->get('prima_estelar');
        $item->prima_manomanista = $request->get('prima_manomanista');
        $item->garantia = $request->get('garantia');
        $item->coste = $request->get('coste');
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

        return response()->json("CONTRATO REMOVED", 200);
    }
}
