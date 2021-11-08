<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PLEN_Sesion;

class PLEN_SesionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $macro_id)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $items = PLEN_Sesion::orderBy('order', 'asc')->get();

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

        $item = new PLEN_Sesion([
          'order' => $request->get('order'),
          'microciclo_id' => $request->get('microciclo_id'),
          'fecha' => $request->get('fecha'),
          'hora' => $request->get('hora'),
          'fronton_id' => $request->get('fronton_id')
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

        $items = PLEN_Sesion::where('microciclo_id', $id)->orderBy('fecha', 'asc')->get();

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

        $item = PLEN_Sesion::find($id);

        $item->order = ( $request->get('order') ? $request->get('order') : $item->order );
        $item->microciclo_id = $request->get('microciclo_id');
        $item->fecha = $request->get('fecha');
        $item->hora = $request->get('hora');
        $item->fronton_id = $request->get('fronton_id');
        $sesion_pelotaris = $item->sesion_pelotaris;
        foreach( $sesion_pelotaris as $sesion_pelotari) {
            $sesion_pelotari->ejercicios()->detach();
        }
        $item->pelotaris()->detach();

        $item->save();

        $pelotaris = $request->get('pelotaris');
        if( $pelotaris ) {
          foreach( $pelotaris as $pelotari ) {
            $item->pelotaris()->attach($pelotari['id']);
            $sesion_pelotari = $item->sesion_pelotaris()->where('pelotari_id', $pelotari['id'])->first();
            foreach( $pelotari['ejercicios'] as $ejercicio ) {
              $sesion_pelotari->ejercicios()->attach($ejercicio['ejercicio_id'], [
                'sesion_pelotari_id' => $sesion_pelotari->id,
                'order' => $ejercicio['order'],
                'fase_sesion_id' => $ejercicio['fase_sesion_id'],
                'ejercicio_id' => $ejercicio['ejercicio_id'],
                'volumen' => $ejercicio['volumen'],
                'intensidad' => $ejercicio['intensidad']
              ]);
            }
          }
        }
        $item->pelotaris = $item->pelotaris;

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

        $sesion = PLEN_Sesion::find($id);
        // foreach( $sesion->ejercicios as $ejercicio ) {
        //   $ejercicio->delete();
        // }
        $sesion->delete();

        return response()->json("SESIÓN ELIMINADA", 200);
    }
}
