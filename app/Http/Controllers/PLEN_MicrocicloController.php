<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PLEN_Microciclo;

class PLEN_MicrocicloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $macro_id)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $items = PLEN_Microciclo::orderBy('order', 'asc')->get();

      return response()->json($items, 200);
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

        $item = new PLEN_Microciclo([
          'order' => $request->get('order'),
          'mesociclo_id' => $request->get('mesociclo_id'),
          'tipo_microciclo_id' => $request->get('tipo_microciclo_id'),
          'fecha_ini' => $request->get('fecha_ini'),
          'fecha_fin' => $request->get('fecha_fin'),
          'volumen' => $request->get('volumen'),
          'intensidad' => $request->get('intensidad'),
          'description' => $request->get('description'),
          'objetivos' => $request->get('objetivos')
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
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        $items = PLEN_Microciclo::where('mesociclo_id', $id)->orderBy('order', 'asc')->get();

        foreach( $items as $index => $microciclo ) {
          $sesiones = \App\PLEN_Sesion::where('microciclo_id', $microciclo->id)->orderBy('fecha', 'ASC')->get();
          $items[$index]->sesiones = $sesiones;
        }

        return response()->json($items, 200);
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

        $data = $request->all();

        $item = PLEN_Microciclo::find($id);

        $item->order = ( $request->get('order') ? $request->get('order') : $item->order );
        $item->mesociclo_id = $request->get('mesociclo_id');
        $item->tipo_microciclo_id = $request->get('tipo_microciclo_id');
        $item->fecha_ini = $request->get('fecha_ini');
        $item->fecha_fin = $request->get('fecha_fin');
        $item->volumen = $request->get('volumen');
        $item->intensidad = $request->get('intensidad');
        $item->description = $request->get('description');
        $item->objetivos = $request->get('objetivos');

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

        $microciclo = PLEN_Microciclo::find($id);
        foreach( $microciclo->sesiones as $sesion ) {
          $sesion->delete();
        }
        $microciclo->delete();

        return response()->json("MICROCICLO ELIMINADO", 200);
    }
}
