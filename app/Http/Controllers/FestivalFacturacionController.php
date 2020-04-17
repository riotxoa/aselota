<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\FestivalFacturacion;

class FestivalFacturacionController extends Controller
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

        $costes = FestivalFacturacion::where('festival_id', $id)->orderBy('id', 'desc')->get();

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

        $facturacion = FestivalFacturacion::find($request->get('id'));
        if( $facturacion ) {
          return $this->update($request, $facturacion->id);
        }

        $facturacion = FestivalFacturacion::where('festival_id', $request->get('festival_id'))->first();

        $facturacion = new FestivalFacturacion([
          'festival_id' => $request->get('festival_id'),
          'fpago_id' => $request->get('fpago_id'),
          'fecha' => $request->get('fecha'),
          'importe' => $request->get('importe'),
          'enviar_id' => $request->get('enviar_id'),
          'observaciones' => $request->get('observaciones'),
          'pagado' => $request->get('pagado'),
          'seguimiento' => $request->get('seguimiento'),
        ]);

        $facturacion->save();

        return response()->json($facturacion, 200);
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

        $facturacion = FestivalFacturacion::find($id);

        return response()->json($facturacion, 200);
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

        $facturacion = FestivalFacturacion::find($id);

        $facturacion->fpago_id = $request->get('fpago_id');
        $facturacion->fecha = $request->get('fecha');
        $facturacion->importe = $request->get('importe');
        $facturacion->enviar_id = $request->get('enviar_id');
        $facturacion->observaciones = $request->get('observaciones');
        $facturacion->pagado = $request->get('pagado');
        $facturacion->seguimiento = $request->get('seguimiento');

        $facturacion->save();

        return response()->json($facturacion, 200);
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

        FestivalFacturacion::destroy($id);

        return response()->json("FACTURACION REMOVED", 200);
    }
}
