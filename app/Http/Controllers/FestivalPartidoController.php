<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Pelotari;
use App\PelotarisAspe;
use App\FestivalPartido;
use App\FestivalPartidoPelotari;
use App\FestivalPartidoTanteo;
use App\FestivalPartidoMarcadore;
use App\TipoPartido;
use Illuminate\Support\Facades\Log;

class FestivalPartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'gerente', 'intendente', 'prensa']);

        $festival_id = $request->get('festival_id');
        $festival_fecha = $request->get('festival_fecha');

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
          $this->getTanteos($partido);
          $this->getMarcadores($partido);
        }

        return response()->json($partidos, 200);
    }

    private function getPelotaris(&$partido, $fecha) {
      $pelotaris = DB::table('festival_partido_pelotaris')
                   ->select('festival_partido_pelotaris.*', 'contratos.fecha_ini', 'contratos.garantia', 'contratos_comercial.coste', 'festivales.fecha_presu')
                   ->leftJoin('festival_partidos', 'festival_partidos.id', '=', 'festival_partido_pelotaris.festival_partido_id')
                   ->leftJoin('festivales', 'festivales.id', '=', 'festival_partidos.festival_id')
                   ->leftJoin('contratos', 'contratos.pelotari_id', '=', DB::raw('festival_partido_pelotaris.pelotari_id AND contratos.fecha_ini <= festivales.fecha AND contratos.fecha_fin >= festivales.fecha'))
                   ->leftJoin('contratos_comercial', 'contratos_comercial.header_id', '=', DB::raw('contratos.header_id AND contratos_comercial.fecha_ini <= festivales.fecha_presu AND contratos_comercial.fecha_fin >= festivales.fecha_presu'))
                   ->where( function ($query) use ($fecha) {
                     $query->where('festival_partido_pelotaris.asegarce', '=', 0)
                           ->orWhere( function ($q) use ($fecha) {
                              $q->where('festival_partido_pelotaris.asegarce', '=', 1)
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
          $pelotari->fecha_contrato = $p->fecha_ini;
          $pelotari->coste = $p->coste;
          $pelotari->garantia = $p->garantia;
          $pelotari->partidos_jugados = Pelotari::get_partidos_jugados_contrato($p->pelotari_id, $fecha);
          $pelotari->asegarce = 1;
        } else {
          $pelotari = PelotarisAspe::find($p->pelotari_id);
          $pelotari->fecha_contrato = null;
          $pelotari->coste = 0;
          $pelotari->garantia = 0;
          $pelotari->partidos_jugados = 0;
          $pelotari->asegarce = 0;
        }

        $pelotari->partido_pelotari_id = $p->id;
        $pelotari->asiste = $p->asiste;
        $pelotari->is_sustituto = $p->is_sustituto;
        $pelotari->sustituto_id = $p->sustituto_id;
        $pelotari->sustituto_alias = "";
        $pelotari->sustituto_coste = 0;
        $pelotari->sustituto_txt = $p->sustituto_txt;
        $pelotari->observaciones = $p->observaciones;

        if( $p->sustituto_id ) {
          $sustituto = Pelotari::find($p->sustituto_id);

          $pelotari->sustituto_alias = $sustituto->alias;

          $sustituto_coste = $sustituto->comercial()->whereDate('fecha_ini', '<=', $p->fecha_presu)->whereDate('fecha_fin', '>=', $p->fecha_presu)->orderBy('created_at', 'desc')->first();
          $pelotari->sustituto_coste = ($sustituto_coste ? $sustituto_coste->coste : null);
        }

        if( "R" === $p->color ) {
          if( "D" === $p->posicion)
            $partido->pelotari_1 = $pelotari;
          else
            $partido->pelotari_2 = $pelotari;
        } else {
          if( "D" === $p->posicion)
            $partido->pelotari_3 = $pelotari;
          else
            $partido->pelotari_4 = $pelotari;
        }
      }
    }

    private function getTanteos(&$partido) {
      $tanteos = FestivalPartidoTanteo::where('festival_partido_id', $partido->id)->get();
      $partido->tanteos = $tanteos;
    }

    private function getMarcadores(&$partido) {
      $marcadores = FestivalPartidoMarcadore::where('festival_partido_id', $partido->id)->get();
      $partido->marcadores = $marcadores;
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
          'duracion' => null,
          'pelotazos' => null,
          'obs_publico' => '',
          'obs_fronton' => '',
          'obs_incidencias' => '',
          'obs_comentarios' => '',
          'puntos_rojo' => 0,
          'puntos_azul' => 0,
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
        $item->duracion = $request->get('duracion');
        $item->pelotazos = $request->get('pelotazos');
        $item->obs_publico = $request->get('obs_publico');
        $item->obs_fronton = $request->get('obs_fronton');
        $item->obs_incidencias = $request->get('obs_incidencias');
        $item->obs_comentarios = $request->get('obs_comentarios');
        $item->puntos_rojo = $request->get('puntos_rojo');
        $item->puntos_azul = $request->get('puntos_azul');

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

    public function update_partido_celebrado(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'gerente', 'intendente']);

      $tanteo = $request->get('tanteo');
      $tantos = $tanteo['tantos'];
      $marcadores = $request->get('marcadores');
      $anotaciones = $request->get('anotaciones');

      $partido = FestivalPartido::find($id);

      $partido->pelotazos = $tanteo['pelotazos'];
      $partido->duracion = $tanteo['duracion'];
      $partido->puntos_rojo = $tanteo['puntos_rojo'];
      $partido->puntos_azul = $tanteo['puntos_azul'];
      $partido->obs_publico = $anotaciones['afluencia'];
      $partido->obs_fronton = $anotaciones['fronton'];
      $partido->obs_incidencias = $anotaciones['incidencias'];
      $partido->obs_comentarios = $anotaciones['comentarios'];

      $partido->save();

      FestivalPartidoTanteo::where('festival_partido_id', $id)->delete();

      $parejas = TipoPartido::find($partido->tipo_partido_id)->parejas;

      foreach( $tantos as $key => $tanto ) {
        if( 1 === $parejas ) :
          $part_tanteo = new FestivalPartidoTanteo([
            'festival_partido_id' => $id,
            'pelotari_id' => $tanteo['pelotari_1_id'],
            'tanteo_order' => ($key + 1) * 10,
            'tanteo_desc' => $tanto['name'],
            'tanteo' => $tanto['pelotari_1'],
          ]);
          $part_tanteo->save();

          $part_tanteo = new FestivalPartidoTanteo([
            'festival_partido_id' => $id,
            'pelotari_id' => $tanteo['pelotari_2_id'],
            'tanteo_order' => ($key + 1) * 10,
            'tanteo_desc' => $tanto['name'],
            'tanteo' => $tanto['pelotari_2'],
          ]);
          $part_tanteo->save();

          $part_tanteo = new FestivalPartidoTanteo([
            'festival_partido_id' => $id,
            'pelotari_id' => $tanteo['pelotari_3_id'],
            'tanteo_order' => ($key + 1) * 10,
            'tanteo_desc' => $tanto['name'],
            'tanteo' => $tanto['pelotari_3'],
          ]);
          $part_tanteo->save();

          $part_tanteo = new FestivalPartidoTanteo([
            'festival_partido_id' => $id,
            'pelotari_id' => $tanteo['pelotari_4_id'],
            'tanteo_order' => ($key + 1) * 10,
            'tanteo_desc' => $tanto['name'],
            'tanteo' => $tanto['pelotari_4'],
          ]);
          $part_tanteo->save();
        else :
          $part_tanteo = new FestivalPartidoTanteo([
            'festival_partido_id' => $id,
            'pelotari_id' => $tanteo['pelotari_1_id'],
            'tanteo_order' => ($key + 1) * 10,
            'tanteo_desc' => $tanto['name'],
            'tanteo' => $tanto['pelotari_1'],
          ]);
          $part_tanteo->save();

          $part_tanteo = new FestivalPartidoTanteo([
            'festival_partido_id' => $id,
            'pelotari_id' => $tanteo['pelotari_3_id'],
            'tanteo_order' => ($key + 1) * 10,
            'tanteo_desc' => $tanto['name'],
            'tanteo' => $tanto['pelotari_3'],
          ]);
          $part_tanteo->save();
        endif;
      }

      FestivalPartidoMarcadore::where('festival_partido_id', $id)->delete();

      foreach( $marcadores['rojo'] as $key => $marcador ) {
        $part_marcador = new FestivalPartidoMarcadore([
          'festival_partido_id' => $id,
          'order' => $key + 1,
          'marcador_rojo' => $marcador,
          'marcador_azul' => $marcadores['azul'][$key],
        ]);
        $part_marcador->save();
      }

      $this->getTanteos($partido);
      $this->getMarcadores($partido);

      return response()->json($partido, 200);
    }

    public function update_pelotari(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'gerente', 'intendente']);

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

        $item->sustituto_coste = $sustituto->comercial()->whereDate('fecha_ini', '<=', $item->festival->fecha_presu)->whereDate('fecha_fin', '>=', $item->festival->fecha_presu)->orderBy('created_at', 'desc')->first()->coste;
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
