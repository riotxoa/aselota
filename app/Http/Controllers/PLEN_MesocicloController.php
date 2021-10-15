<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PLEN_Mesociclo;

class PLEN_MesocicloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $macro_id)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $mesociclos = PLEN_Mesociclo::orderBy('order', 'asc')->get();

      return response()->json($mesociclos, 200);
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

        $item = new PLEN_Mesociclo([
          'order' => $request->get('order'),
          'macrociclo_id' => $request->get('macrociclo_id'),
          'tipo_mesociclo_id' => $request->get('tipo_mesociclo_id'),
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
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        $items = PLEN_Mesociclo::where('macrociclo_id', $id)->orderBy('order', 'asc')->get();

        foreach( $items as $index01 => $mesociclo ) {
          $microciclos = \App\PLEN_Microciclo::where('mesociclo_id', $mesociclo->id)->orderBy('fecha_ini', 'ASC')->get();
          foreach( $microciclos as $index02 => $microciclo ) {
            $sesiones = \App\PLEN_Sesion::where('microciclo_id', $microciclo->id)->orderBy('fecha', 'ASC')->get();
            foreach( $sesiones as $index03 => $sesion ) {
              $sesiones[$index03]->pelotaris = $sesion->pelotaris()->select('id', 'alias', 'nombre', 'apellidos', 'email', 'telefono', 'foto', 'fecha_nac')->get();
            }
            $microciclos[$index02]->sesiones = $sesiones;
          }
          $items[$index01]->microciclos = $microciclos;
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

        $item = PLEN_Mesociclo::find($id);

        $item->order = ( $request->get('order') ? $request->get('order') : $item->order );
        $item->macrociclo_id = $request->get('macrociclo_id');
        $item->tipo_mesociclo_id = $request->get('tipo_mesociclo_id');
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

        $mesociclo = PLEN_Mesociclo::find($id);
        foreach( $mesociclo->microciclos as $microciclo ) {
          foreach( $microciclo->sesiones as $sesion ) {
            $sesion->delete();
          }
          $microciclo->delete();
        }
        $mesociclo->delete();

        return response()->json("MESOCILO ELIMINADO", 200);
    }
}
