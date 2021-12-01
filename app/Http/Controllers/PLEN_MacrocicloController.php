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

      $macrociclo = PLEN_Macrociclo::find($id);
      foreach( $macrociclo->mesociclos as $mesociclo ) {
        foreach( $mesociclo->microciclos as $microciclo ) {
          foreach( $microciclo->sesiones as $sesion ) {
            foreach( $sesion->sesion_pelotaris as $pelotari ) {
              foreach( $pelotari->ejercicios as $ejercicio ) {
                $ejercicio->delete();
              }
              $pelotari->delete();
            }
            $sesion->delete();
          }
          $microciclo->delete();
        }
        $mesociclo->delete();
      }
      $macrociclo->delete();

      return response()->json("MACROCILO ELIMINADO", 200);
    }

    public function getActiveItems(Request $request, $date)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor', 'plen_entrenador']);

      $fecha = date('Y-m-d', $date/1000);

      $macrociclos = PLEN_Macrociclo::whereDate('fecha_ini', '<=', $fecha)->whereDate('fecha_fin', '>=', $fecha)->get();
      $mesociclos = \App\PLEN_Mesociclo::select('plen_mesociclos.*', 'plen_tipos_mesociclo.desc as tipo_mesociclo', 'plen_tipos_mesociclo.color')->leftJoin('plen_tipos_mesociclo', 'plen_tipos_mesociclo.id', '=', 'plen_mesociclos.tipo_mesociclo_id')->whereDate('fecha_ini', '<=', $fecha)->whereDate('fecha_fin', '>=', $fecha)->get();
      $microciclos = \App\PLEN_Microciclo::select('plen_microciclos.*', 'plen_tipos_microciclo.desc as tipo_microciclo')->leftJoin('plen_tipos_microciclo', 'plen_tipos_microciclo.id', '=', 'plen_microciclos.tipo_microciclo_id')->whereDate('fecha_ini', '<=', $fecha)->whereDate('fecha_fin', '>=', $fecha)->get();
      $sesiones = \App\PLEN_Sesion::select('plen_sesiones.*', 'frontones.name as fronton')->leftJoin('frontones', 'frontones.id', '=', 'plen_sesiones.fronton_id')->whereDate('fecha', $fecha)->get();
      $sesion_ids = \App\PLEN_Sesion::whereDate('fecha', $fecha)->pluck('id');
      $pelotaris = \App\PLEN_SesionPelotari::select('plen_sesion_pelotaris.*', 'pelotaris.alias')->leftJoin('pelotaris', 'pelotaris.id', '=', 'plen_sesion_pelotaris.pelotari_id')->whereIn('sesion_id', $sesion_ids)->get();
      $pelotari_ids = \App\PLEN_SesionPelotari::select('plen_sesion_pelotaris.*', 'pelotaris.alias')->leftJoin('pelotaris', 'pelotaris.id', '=', 'plen_sesion_pelotaris.pelotari_id')->whereIn('sesion_id', $sesion_ids)->pluck('id');
      $ejercicios = \App\PLEN_SesionEjercicio::select('plen_sesion_ejercicios.*', 'plen_ejercicios.name', 'plen_ejercicios.material')->leftJoin('plen_ejercicios', 'plen_ejercicios.id', '=', 'plen_sesion_ejercicios.ejercicio_id')->whereIn('sesion_pelotari_id', $pelotari_ids)->get();

      $items = [
        'macrociclos' => $macrociclos,
        'mesociclos' => $mesociclos,
        'microciclos' => $microciclos,
        'sesiones' => $sesiones,
        'pelotaris' => $pelotaris,
        'ejercicios' => $ejercicios,
        'fecha' => $fecha
      ];

      return response()->json($items, 200);
    }

    private function getActiveItemsBetweenDates($fecha_ini, $fecha_fin)
    {
      // $macrociclos = PLEN_Macrociclo::whereBetween($fecha_ini, ['fecha_ini', 'fecha_fin'])->orWhereBetween($fecha_fin, ['fecha_ini', 'fecha_fin'])->get();
      $macrociclos = PLEN_Macrociclo::select('plen_macrociclos.*')
        ->whereRaw("'$fecha_ini' between fecha_ini and fecha_fin")
        ->orWhereRaw("'$fecha_fin' between fecha_ini and fecha_fin")
        ->orWhereRaw("('$fecha_ini' <= fecha_ini and '$fecha_fin' >= fecha_fin)")
        ->orderBy('fecha_ini', 'ASC')
        ->get();
      $mesociclos = \App\PLEN_Mesociclo::select('plen_mesociclos.*', 'plen_tipos_mesociclo.desc as tipo_mesociclo', 'plen_tipos_mesociclo.color')
        ->leftJoin('plen_tipos_mesociclo', 'plen_tipos_mesociclo.id', '=', 'plen_mesociclos.tipo_mesociclo_id')
        ->whereRaw("'$fecha_ini' between fecha_ini and fecha_fin")
        ->orWhereRaw("'$fecha_fin' between fecha_ini and fecha_fin")
        ->orWhereRaw("('$fecha_ini' <= fecha_ini and '$fecha_fin' >= fecha_fin)")
        ->orderBy('fecha_ini', 'ASC')
        ->get();
      $microciclos = \App\PLEN_Microciclo::select('plen_microciclos.*', 'plen_tipos_microciclo.desc as tipo_microciclo')
        ->leftJoin('plen_tipos_microciclo', 'plen_tipos_microciclo.id', '=', 'plen_microciclos.tipo_microciclo_id')
        ->whereRaw("$fecha_ini between fecha_ini and fecha_fin")
        ->orWhereRaw("$fecha_fin between fecha_ini and fecha_fin")
        ->orWhereRaw("('$fecha_ini' <= fecha_ini and '$fecha_fin' >= fecha_fin)")
        ->orderBy('fecha_ini', 'ASC')
        ->get();
      $sesiones = \App\PLEN_Sesion::select('plen_sesiones.*', 'frontones.name as fronton')
        ->leftJoin('frontones', 'frontones.id', '=', 'plen_sesiones.fronton_id')
        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
        ->orderBy('fecha', 'ASC')
        ->get();
      $sesion_ids = \App\PLEN_Sesion::whereBetween('fecha', [$fecha_ini, $fecha_fin])->pluck('id');
      $pelotaris = \App\PLEN_SesionPelotari::select('plen_sesion_pelotaris.*', 'pelotaris.alias')
        ->leftJoin('pelotaris', 'pelotaris.id', '=', 'plen_sesion_pelotaris.pelotari_id')
        ->whereIn('sesion_id', $sesion_ids)
        ->get();
      $pelotari_ids = \App\PLEN_SesionPelotari::select('plen_sesion_pelotaris.*', 'pelotaris.alias')
        ->leftJoin('pelotaris', 'pelotaris.id', '=', 'plen_sesion_pelotaris.pelotari_id')
        ->whereIn('sesion_id', $sesion_ids)
        ->pluck('id');
      $ejercicios = \App\PLEN_SesionEjercicio::select('plen_sesion_ejercicios.*', 'plen_ejercicios.name', 'plen_ejercicios.material')
        ->leftJoin('plen_ejercicios', 'plen_ejercicios.id', '=', 'plen_sesion_ejercicios.ejercicio_id')
        ->whereIn('sesion_pelotari_id', $pelotari_ids)
        ->get();

      return [
        'macrociclos' => $macrociclos,
        'mesociclos' => $mesociclos,
        'microciclos' => $microciclos,
        'sesiones' => $sesiones,
        'pelotaris' => $pelotaris,
        'ejercicios' => $ejercicios
      ];
    }

    public function getActiveItemsByMonth(Request $request, $date)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor', 'plen_entrenador']);

      $fecha_ini = date('Y-m-01', $date/1000);
      $fecha_fin = date('Y-m-t', $date/1000);

      return response()->json($this->getActiveItemsBetweenDates($fecha_ini, $fecha_fin), 200);
    }

    public function getActiveItemsByWeek(Request $request, $date)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor', 'plen_entrenador']);

      $fecha = date('Y-m-d', $date/1000);

      $fecha_ini = date('Y-m-d', strtotime($fecha . ' monday this week'));
      $fecha_fin = date('Y-m-d', strtotime($fecha . ' sunday this week'));

      return response()->json($this->getActiveItemsBetweenDates($fecha_ini, $fecha_fin), 200);
    }

    public function getActiveItemsByYear(Request $request, $date)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor', 'plen_entrenador']);

      // $fecha_ini = date('Y-01-01', $date/1000);
      // $fecha_fin = date('Y-12-31', $date/1000);

      $fecha_ini_new = date('Y-01-01', strtotime("-1 year", $date/1000));
      $fecha_fin_new = date('Y-12-31', strtotime("+1 year", $date/1000));

      return response()->json($this->getActiveItemsBetweenDates($fecha_ini_new, $fecha_fin_new), 200);
    }

    public function getActiveMacrociclos(Request $request, $date)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor', 'plen_entrenador']);

      $fecha = date('Y-m-d', $date/1000);
      $items = PLEN_Macrociclo::whereDate('fecha_ini', '<=', $fecha)->whereDate('fecha_fin', '>=', $fecha)->orderBy('fecha_ini', 'ASC')->get();

      return response()->json($items, 200);
    }
}
