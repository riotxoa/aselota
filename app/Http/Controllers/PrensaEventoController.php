<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PrensaEvento;
use App\PrensaPelotari;

class PrensaEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $items = DB::table('prensa_eventos')
                 ->select('prensa_eventos.*', 'provincias.name as provincia_name', 'municipios.name as municipio_name', 'prensa_motivos.name as motivo_name')
                 ->leftJoin('provincias', 'prensa_eventos.provincia_id', '=', 'provincias.id')
                 ->leftJoin('municipios', 'prensa_eventos.municipio_id', '=', 'municipios.id')
                 ->leftJoin('prensa_motivos', 'prensa_eventos.motivo_id', '=', 'prensa_motivos.id')
                 ->orderBy('fecha', 'desc')
                 ->get();

      foreach($items as $key => $item) {
        $pelotaris = DB::table('prensa_evento_pelotaris')
                      ->select('prensa_evento_pelotaris.*', 'pelotaris.*')
                      ->where('prensa_evento_id', $item->id)
                      ->leftJoin('pelotaris', 'pelotaris.id', '=', 'prensa_evento_pelotaris.pelotari_id')
                      ->get();
        $items[$key]->pelotaris = $pelotaris;
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
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $item = new PrensaEvento([
        'motivo_id' => $request->get('motivo_id'),
        'campeonato_id' => $request->get('campeonato_id'),
        'provincia_id' => $request->get('provincia_id'),
        'municipio_id' => $request->get('municipio_id'),
        'desc' => $request->get('desc'),
        'fecha' => $request->get('fecha'),
        'hora' => $request->get('hora'),
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
        //
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
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $item = PrensaEvento::find($id);

      $item->motivo_id = $request->get('motivo_id');
      $item->campeonato_id = $request->get('campeonato_id');
      $item->provincia_id = $request->get('provincia_id');
      $item->municipio_id = $request->get('municipio_id');
      $item->desc = $request->get('desc');
      $item->fecha = $request->get('fecha');
      $item->hora = $request->get('hora');

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
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $evento = PrensaEvento::find($id);

      if( $evento ) {
        $pelotaris = $evento->pelotaris();

        foreach($pelotaris as $p) {
          PrensaPelotari::destroy($p->id);
        }
      }

      PrensaEvento::destroy($id);

      return response()->json("EVENTO REMOVED", 200);
    }

    public function get_pelotaris(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $items = PrensaEvento::find($id)->pelotaris();

      return response()->json($items, 200);
    }

    public function add_pelotari(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $item = new PrensaPelotari([
        'prensa_evento_id' => $id,
        'pelotari_id' => $request->value,
        'asiste' => $request->asiste,
        'motivo' => $request->motivo,
      ]);
      $item->save();

      $pelotari = DB::table('prensa_evento_pelotaris')
                    ->select('prensa_evento_pelotaris.*', 'pelotaris.*')
                    ->where('pelotaris.id', '=', $request->value)
                    ->leftJoin('pelotaris', 'pelotaris.id', '=', 'prensa_evento_pelotaris.pelotari_id')
                    ->first();
      $pelotari->asiste = $request->asiste;
      $pelotari->motivo = $request->motivo;

      return response()->json($pelotari, 200);
    }

    public function update_pelotari(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'prensa']);

      $pelotari = PrensaPelotari::where('prensa_evento_id', $id)->where('pelotari_id', $request->id)->first();

      $pelotari->asiste = $request->asiste;
      $pelotari->motivo = $request->motivo;

      $pelotari->save();

      return response()->json($pelotari, 200);
    }

    public function delete_pelotari(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'prensa']);

      PrensaPelotari::where('prensa_evento_id', $id)->where('pelotari_id', $request->id)->delete();

      return response()->json("PELOTARI $request->id ELIMINADO DEL EVENTO $id; Resquest: " . json_encode($request->all()), 200);
    }
}
