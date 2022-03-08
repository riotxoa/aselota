<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CampeonatoController extends \App\Http\Controllers\Controller
{
    public function index()
    {
      $campeonatos = \App\Campeonato::select('id', 'name')->orderBy('name', 'asc')->get();
      $retorno = array();

      foreach ($campeonatos as $campeonato)
      {
        $finales = $this->getFechasFinales($campeonato->id);

        foreach ($finales as $stepFinales => $final)
        {
          $fechaMaxima = $final->fecha;
          $fechaMinima = ($stepFinales + 1 < sizeof($finales) - 1 ? date('Y-m-d', strtotime($finales[($stepFinales + 1)]->fecha . ' +1 day')) : '1970-01-01');

          $fases = $this->getFases($campeonato->id, $fechaMinima, $fechaMaxima);

          $item = array(
            'id' => $campeonato->id,
            'name' => $campeonato->name,
            'fecha_final' => $final->fecha,
            'fases' => $fases
          );

          array_push($retorno, $item);
        }
      }

      return response()->json($retorno, 200);
    }

    private function getFechasFinales( $campeonatoId )
    {
      $fechasFinales = DB::table('festival_partidos as partidos')
                      ->select('festivales.fecha')
                      ->leftJoin('festivales', 'festivales.id', '=', 'partidos.festival_id')
                      ->where('partidos.campeonato_id', '=', $campeonatoId)
                      ->where('partidos.fase', '=', 'final')
                      ->orderBy('festivales.fecha', 'desc')
                      ->get();
      return ($fechasFinales ? $fechasFinales : '1990-01-01');
    }

    private function getFases( $campeonatoId, $fechaMinima, $fechaMaxima )
    {
      $retorno = array();

      $fases = \App\FestivalPartido::distinct()
        ->leftJoin('festivales', 'festivales.id', 'festival_partidos.festival_id')
        ->where('festival_partidos.campeonato_id', $campeonatoId)
        ->whereDate('festivales.fecha', '>=', $fechaMinima)
        ->whereDate('festivales.fecha', '<=', $fechaMaxima)
        ->get(['festival_partidos.fase']);

      foreach ($fases as $fase)
      {
        $key = ($fase->fase ? $fase->fase : 'null');
        $retorno[$key] = $this->getPartidos( $campeonatoId, $fechaMinima, $fechaMaxima, $key);
      }

      return $retorno;
    }

    private function getPartidos( $campeonatoId, $fechaMinima, $fechaMaxima, $fase )
    {
      $partidos = \App\FestivalPartido::select(
          'festival_partidos.id as partido_id',
          'festivales.fecha',
          'festivales.hora',
          'festivales.television',
          'frontones.id as fronton_id',
          'frontones.name as fronton',
          'municipios.name as municipio',
          'festival_partidos.fase',
          'festival_partidos.puntos_rojo',
          'festival_partidos.puntos_azul',
          DB::raw('IF(festival_partidos.puntos_rojo > festival_partidos.puntos_azul, "equipo_rojo", "equipo_azul") as ganador')
        )
        ->leftJoin('festivales', 'festivales.id', 'festival_partidos.festival_id')
        ->leftJoin('frontones', 'frontones.id', 'festivales.fronton_id')
        ->leftJoin('municipios', 'municipios.id', 'frontones.municipio_id')
        ->where('festival_partidos.campeonato_id', $campeonatoId)
        ->where('festival_partidos.fase', ($fase !== 'null' ? $fase : null))
        ->whereDate('festivales.fecha', '>=', $fechaMinima)
        ->whereDate('festivales.fecha', '<=', $fechaMaxima)
        ->orderBy('festivales.fecha', 'ASC')
        ->get();

      foreach ($partidos as $partido)
      {
        $pelotaris = $this->getPelotaris($partido->partido_id);

        $partido->equipo_rojo = $pelotaris['R'];
        $partido->equipo_azul = $pelotaris['A'];
      }

      return $partidos;
    }

    private function getPelotaris( $partidoId )
    {
      $pelotaris_partido = array(
        'R' => array(),
        'A' => array()
      );

      $pelotaris = \App\FestivalPartidoPelotari::select( 'festival_partido_pelotaris.*' )
        ->where('festival_partido_id', $partidoId)
        ->orderBy('color', 'desc')
        ->orderBy('posicion', 'asc')
        ->get();

      foreach( $pelotaris as $pelotari )
      {
        if( $pelotari->asegarce )
        {
          $pelotari_partido = \App\Pelotari::select(
            'pelotaris.id as pelotari_id',
            'pelotaris.alias',
            'pelotaris.posicion'
          )
          ->where('pelotaris.id', $pelotari->pelotari_id)
          ->first();
          $pelotari_partido->empresa = "BAIKO";
        }
        else
        {
          $pelotari_partido = \App\PelotarisAspe::select(
            'pelotaris_aspe.id as pelotari_id',
            'pelotaris_aspe.alias',
            'pelotaris_aspe.posicion'
          )
          ->where('pelotaris_aspe.id', $pelotari->pelotari_id)
          ->first();
          $pelotari_partido->empresa = "ASPE";
        }
        array_push( $pelotaris_partido[$pelotari->color], $pelotari_partido );
      }

      return $pelotaris_partido;
    }
}
