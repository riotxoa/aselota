<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Pelotari;
use App\PelotarisAspe;
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
        $request->user()->authorizeRoles(['admin', 'gerente', 'intendente']);

        $festival_id = $request->get('festival_id');
        $festival_fecha = $request->get('festival_fecha');

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
          ->orderBy('partido.orden', 'asc')
          ->get();

        foreach( $partidos as $partido ) {
          $this->getPelotaris($partido, $festival_fecha);
        }

        return response()->json($partidos, 200);
    }

    private function getPelotaris(&$partido, $fecha) {
      $pelotaris = DB::table('festival_partido_pelotaris')
                   ->select('festival_partido_pelotaris.*', 'contratos.coste')
                   ->leftJoin('contratos', 'contratos.pelotari_id', '=', 'festival_partido_pelotaris.pelotari_id')
                   ->where( function ($query) use ($fecha) {
                     $query->where('festival_partido_pelotaris.asegarce', '=', 0)
                           ->orWhere( function ($q) use ($fecha) {
                              $q->where('festival_partido_pelotaris.asegarce', '=', 1)
                                ->whereDate('contratos.fecha_ini', '<=', $fecha)
                                ->whereDate('contratos.fecha_fin', '>=', $fecha)
                                ->whereNull('contratos.deleted_at');
                             }
                           );
                   })
                   ->where('festival_partido_pelotaris.is_sustituto', '=', 0)
                   ->where('festival_partido_pelotaris.festival_partido_id', '=', $partido->id)
                   ->orderBy('festival_partido_pelotaris.color', 'desc')
                   ->orderBy('festival_partido_pelotaris.posicion', 'asc')
                   ->get();

      foreach( $pelotaris as $key => $p ) {

        if( $p->asegarce ) {
          $pelotari = Pelotari::find($p->pelotari_id);
          $pelotari->coste = $p->coste;
          $pelotari->asegarce = 1;
        } else {
          $pelotari = PelotarisAspe::find($p->pelotari_id);
          $pelotari->coste = 0;
          $pelotari->asegarce = 0;
        }

        $pelotari->partido_pelotari_id = $p->id;
        $pelotari->asiste = $p->asiste;
        $pelotari->is_sustituto = $p->is_sustituto;
        $pelotari->sustituto_id = $p->sustituto_id;
        $pelotari->sustituto_alias = "";
        $pelotari->sustituto_txt = $p->sustituto_txt;
        $pelotari->observaciones = $p->observaciones;

        if( $p->sustituto_id ) {
          $sustituto = Pelotari::find($p->sustituto_id);
          $pelotari->sustituto_alias = $sustituto->alias;
        }

        if( "R" === $p->color ) {
          if( "D" === $p->posicion)
            $partido->pelotari_1 = $pelotari; // Pelotari::find($p->pelotari_id);
          else
            $partido->pelotari_2 = $pelotari; // Pelotari::find($p->pelotari_id);
        } else {
          if( "D" === $p->posicion)
            $partido->pelotari_3 = $pelotari; // Pelotari::find($p->pelotari_id);
          else
            $partido->pelotari_4 = $pelotari; // Pelotari::find($p->pelotari_id);
        }
      }
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

        $item->campeonato_name = ($request->get('campeonato_id') ? $request->get('campeonato_name') : null);
        $item->tipo_partido_name = $request->get('tipo_partido_name');
        $item->is_partido_parejas = $request->get('is_partido_parejas');

        $pelotari_1 = new FestivalPartidoPelotari([
          'festival_partido_id' => $item->id,
          'color' => 'R',
          'posicion' => 'D',
          'pelotari_id' => $request->get('pelotari_1')['id'],
          'asegarce' => $request->get('pelotari_1_asegarce'),
        ]);
        $pelotari_1->save();

        if($request->get('pelotari_2')) {
          $pelotari_2 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'R',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_2')['id'],
            'asegarce' => $request->get('pelotari_2_asegarce'),
          ]);
          $pelotari_2->save();
        }

        $pelotari_3 = new FestivalPartidoPelotari([
          'festival_partido_id' => $item->id,
          'color' => 'A',
          'posicion' => 'D',
          'pelotari_id' => $request->get('pelotari_3')['id'],
          'asegarce' => $request->get('pelotari_3_asegarce'),
        ]);
        $pelotari_3->save();

        if($request->get('pelotari_4')) {
          $pelotari_4 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'A',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_4')['id'],
            'asegarce' => $request->get('pelotari_4_asegarce'),
          ]);
          $pelotari_4->save();
        }

        $this->getPelotaris($item, $request->get('fecha'));

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

        $item->campeonato_name = ($request->get('campeonato_id') ? $request->get('campeonato_name') : null);
        $item->tipo_partido_name = $request->get('tipo_partido_name');
        $item->is_partido_parejas = $request->get('is_partido_parejas');

        DB::table('festival_partido_pelotaris')->where('festival_partido_id', '=', $id)->delete();

        if($request->get('pelotari_1')) {
          $pelotari_1 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'R',
            'posicion' => 'D',
            'pelotari_id' => $request->get('pelotari_1')['id'],
            'asegarce' => $request->get('pelotari_1_asegarce'),
          ]);
          $pelotari_1->save();
        }
        if($request->get('pelotari_2')) {
          $pelotari_2 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'R',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_2')['id'],
            'asegarce' => $request->get('pelotari_2_asegarce'),
          ]);
          $pelotari_2->save();
        }
        if($request->get('pelotari_3')) {
          $pelotari_3 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'A',
            'posicion' => 'D',
            'pelotari_id' => $request->get('pelotari_3')['id'],
            'asegarce' => $request->get('pelotari_3_asegarce'),
          ]);
          $pelotari_3->save();
        }
        if($request->get('pelotari_4')) {
          $pelotari_4 = new FestivalPartidoPelotari([
            'festival_partido_id' => $item->id,
            'color' => 'A',
            'posicion' => 'Z',
            'pelotari_id' => $request->get('pelotari_4')['id'],
            'asegarce' => $request->get('pelotari_4_asegarce'),
          ]);
          $pelotari_4->save();
        }

        $this->getPelotaris($item, $request->get('fecha'));

        return response()->json($item, 200);
    }

    public function update_pelotari(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'intendente']);

      $item = FestivalPartidoPelotari::find($id);

      $item->asiste = $request->get('asiste');
      $item->sustituto_id = $request->get('sustituto_id');
      $item->sustituto_txt = $request->get('sustituto_txt');
      $item->observaciones = $request->get('observaciones');

      $item->save();

      $sustituto = FestivalPartidoPelotari::where('festival_partido_id', $item->festival_partido_id)
                      ->where('color', $item->color)
                      ->where('posicion', $item->posicion)
                      ->where('is_sustituto', 1)
                      ->first();

      if($item->asiste) {
        if($sustituto)
          FestivalPartidoPelotari::destroy($sustituto->id);
      } else {
        if(!$sustituto)
          $sustituto = new FestivalPartidoPelotari();

        $sustituto->festival_partido_id = $item->festival_partido_id;
        $sustituto->color = $item->color;
        $sustituto->posicion = $item->posicion;
        $sustituto->pelotari_id = $item->sustituto_id;
        $sustituto->asegarce = $item->asegarce;
        $sustituto->asiste = 1;
        $sustituto->is_sustituto = 1;
        $sustituto->save();
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
