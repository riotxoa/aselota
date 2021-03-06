<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\ContratoCampeonato;

class ContratoCampeonatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $pelotari_id = $request->get('pelotari_id');
        $campeonato_id = $request->get('campeonato_id');

        // $items = ContratoCampeonato::where('pelotari_id', $pelotari_id)->where('campeonato_id', $campeonato_id)->get();

        $items = DB::table('contrato_campeonatos')
          ->select(
              'contrato_campeonatos.*',
              'contratos_header.name',
              'contratos_header.fecha_ini',
              'contratos_header.fecha_fin'
            )
          ->leftJoin('contratos_header', 'contratos_header.id', '=', 'contrato_campeonatos.header_id')
          ->where('contrato_campeonatos.pelotari_id', '=', $pelotari_id)
          ->where('contrato_campeonatos.campeonato_id', '=', $campeonato_id)
          ->where('contrato_campeonatos.deleted_at', '=', null)
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

        $item = new ContratoCampeonato([
          'header_id' => $request->get('header_id'),
          'pelotari_id' => $request->get('pelotari_id'),
          'campeonato_id' => $request->get('campeonato_id'),
          'campeon' => $request->get('campeon'),
          'subcampeon' => $request->get('subcampeon'),
          'final' => $request->get('final'),
          'liga_semifinal' => $request->get('liga_semifinal'),
          'liga_cuartos' => $request->get('liga_cuartos'),
          'semifinal' => $request->get('semifinal'),
          'cuartos' => $request->get('cuartos'),
          'octavos' => $request->get('octavos'),
          'dieciseisavos' => $request->get('dieciseisavos'),
          'treintaidosavos' => $request->get('treintaidosavos'),
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

        $item = ContratoCampeonato::find($id);

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

        $item = ContratoCampeonato::find($id);

        $item->header_id = $request->get('header_id');
        $item->campeon = $request->get('campeon');
        $item->subcampeon = $request->get('subcampeon');
        $item->final = $request->get('final');
        $item->liga_semifinal = $request->get('liga_semifinal');
        $item->liga_cuartos = $request->get('liga_cuartos');
        $item->semifinal = $request->get('semifinal');
        $item->cuartos = $request->get('cuartos');
        $item->octavos = $request->get('octavos');
        $item->dieciseisavos = $request->get('dieciseisavos');
        $item->treintaidosavos = $request->get('treintaidosavos');

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

        ContratoCampeonato::destroy($id);

        return response()->json("CONTRATO CAMPEONATO REMOVED", 200);
    }
}
