<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PLEN_TipoEjercicio;

class PLEN_TipoEjercicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $clientes = PLEN_TipoEjercicio::orderBy('order', 'asc')->get();

      return response()->json($clientes, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        $item = new PLEN_TipoEjercicio([
          'order' => $request->get('order'),
          'desc' => $request->get('desc')
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
    public function show($id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);
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
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        $item = PLEN_TipoEjercicio::find($id);

        $item->order = $request->get('order');
        $item->desc = $request->get('desc');

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
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        $subtipos = \App\PLEN_SubtipoEjercicio::where('plen_tipos_ejercicio_id', $id)->count();

        if( $subtipos > 0 ) {
          return response()->json('Existen subtipos vinculados a este Tipo de Ejercicio (ID: ' . $id . ')', 409);
        }

        PLEN_TipoEjercicio::destroy($id);

        return response()->json("TIPO EJERCICIO ELIMINADO", 200);
    }
}
