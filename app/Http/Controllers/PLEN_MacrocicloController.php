<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PLEN_Macrociclo;

class PLEN_MacrocicloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $items = PLEN_Macrociclo::orderBy('order', 'asc')->get();

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

        $item = new PLEN_Macrociclo([
          'order' => $request->get('order'),
          'fecha_ini' => $request->get('fecha_ini'),
          'fecha_fin' => $request->get('fecha_fin'),
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

        $item = PLEN_Macrociclo::find($id);

        $item->order = $request->get('order');
        $item->fecha_ini = $request->get('fecha_ini');
        $item->fecha_fin = $request->get('fecha_fin');
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

        $mesociclos = \App\PLEN_Mesociclo::where('macrociclo_id', '=', $id)->get();
        foreach( $mesociclos as $mesociclo ) {
          $microciclos = \App\PLEN_Microciclo::where('mesociclo_id', '=', $mesociclo->$id)->get();
          foreach( $microciclos as $microciclo ) {
            $sesiones = \App\PLEN_Sesion::where('microciclo_id', '=', $microciclo->id)->get();
            foreach( $sesiones as $sesion ) {
              \App\PLEN_Sesion::destroy($sesion->id);
            }
            \App\PLEN_Microciclo::destroy($microciclo->id);
          }
          \App\PLEN_Mesociclo::destroy($mesociclo->id);
        }

        PLEN_Macrociclo::destroy($id);

        return response()->json("MACROCILO ELIMINADO", 200);
    }
}
