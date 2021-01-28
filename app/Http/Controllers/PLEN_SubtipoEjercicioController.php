<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PLEN_SubtipoEjercicio;

class PLEN_SubtipoEjercicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $clientes = PLEN_SubtipoEjercicio::orderBy('desc', 'asc')->get();

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

        $item = new PLEN_SubtipoEjercicio([
          'plen_tipos_ejercicio_id' => $request->get('plen_tipos_ejercicio_id'),
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

        $item = PLEN_SubtipoEjercicio::find($id);

        $item->plen_tipos_ejercicio_id = $request->get('plen_tipos_ejercicio_id');
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
    public function destroy($id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        PLEN_SubtipoEjercicio::destroy($id);

        return response()->json("SUBTIPO EJERCICIO ELIMINADO", 200);
    }
}
