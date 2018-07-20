<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\FestivalPartido;
use App\FestivalPartidoPelotari;

class FestivalPartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $festival_id = $request->get('festival_id');

        // $items = FestivalPartido::where('festival_id', $festival_id)->get();

        $partidos = DB::table('festival_partidos as partido')
          ->select(
              'partido.*',
              'campeonatos.name as campeonato_name',
              'tipo_partidos.name as tipo_partido_name',
              'tipo_partidos.parejas as is_partido_parejas'
            )
          ->leftJoin('campeonatos', 'campeonatos.id', '=', 'partido.campeonato_id')
          ->leftJoin('tipo_partidos', 'tipo_partidos.id', '=', 'partido.tipo_partido_id')
          ->where('partido.festival_id', '=', $festival_id)
          ->where('partido.deleted_at', '=', null)
          ->orderBy('partido.orden', 'asc')
          ->get();

        foreach( $partidos as $partido ) {
          $pelotaris = FestivalPartidoPelotari::where('festival_partido_id', $partido->id)->orderBy('color', 'desc')->orderBy('posicion', 'asc')->get();
          foreach( $pelotaris as $key => $p ) {
            if( "R" === $p->color ) {
              if( "D" === $p->posicion)
                $partido->pelotari_1 = $p->pelotari;
              else
                $partido->pelotari_2 = $p->pelotari;
            } else {
              if( "D" === $p->posicion)
                $partido->pelotari_3 = $p->pelotari;
              else
                $partido->pelotari_4 = $p->pelotari;
            }
          }
        }

        return response()->json($partidos, 200);
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

        $item = new FestivalPartido([
          'festival_id' => $request->get('festival_id'),
          'orden' => $request->get('orden'),
          'estelar' => $request->get('estelar'),
          'campeonato_id' => $request->get('campeonato_id'),
          'tipo_partido_id' => $request->get('tipo_partido_id'),
          'fase' => $request->get('fase'),
        ]);

        $item->save();

        $pelotari_1 = new FestivalPartidoPelotari([
          'festival_partido_id' => $item->id,
          'color' => 'R',
          'posicion' => 'D',
          'pelotari_id' => $request->get('pelotari_1')['id'],
        ]);
        $pelotari_1->save();

        if($request->get('pelotari_2')) {
          $pelotari_2 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'R',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_2')['id'],
          ]);
          $pelotari_2->save();
        }

        $pelotari_3 = new FestivalPartidoPelotari([
          'festival_partido_id' => $item->id,
          'color' => 'A',
          'posicion' => 'D',
          'pelotari_id' => $request->get('pelotari_3')['id'],
        ]);
        $pelotari_3->save();

        if($request->get('pelotari_4')) {
          $pelotari_4 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'A',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_4')['id'],
          ]);
          $pelotari_4->save();
        }

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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $item = FestivalPartido::find($id);

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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $item = FestivalPartido::find($id);

        $item->festival_id = $request->get('festival_id');
        $item->orden = $request->get('orden');
        $item->estelar = $request->get('estelar');
        $item->campeonato_id = $request->get('campeonato_id');
        $item->tipo_partido_id = $request->get('tipo_partido_id');
        $item->fase = $request->get('fase');

        $item->save();

        DB::table('festival_partido_pelotaris')->where('festival_partido_id', '=', $id)->delete();

        if($request->get('pelotari_1')) {
          $pelotari_1 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'R',
            'posicion' => 'D',
            'pelotari_id' => $request->get('pelotari_1')['id'],
          ]);
          $pelotari_1->save();
        }
        if($request->get('pelotari_2')) {
          $pelotari_2 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'R',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_2')['id'],
          ]);
          $pelotari_2->save();
        }
        if($request->get('pelotari_3')) {
          $pelotari_3 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'A',
            'posicion' => 'D',
            'pelotari_id' => $request->get('pelotari_3')['id'],
          ]);
          $pelotari_3->save();
        }
        if($request->get('pelotari_4')) {
          $pelotari_4 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'A',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_4')['id'],
          ]);
          $pelotari_4->save();
        }

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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        FestivalPartido::destroy($id);

        $pelotaris = FestivalPartidoPelotari::where('festival_partido_id', $id)->get();
        foreach($pelotaris as $pelotari)
          FestivalPartidoPelotari::destroy($pelotari->id);

        return response()->json("FESTIVAL PARTIDO REMOVED", 200);
    }
}
