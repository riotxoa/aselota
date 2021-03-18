<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Festivale as Festival;
use App\FestivalPartido as Partido;
use App\FestivalPartidoPelotari as Pelotari;
use App\Pelotari as PelotariBaiko;
use App\PelotarisAspe as PelotariAspe;

use Illuminate\Support\Facades\Log;

use PDF; // https://www.positronx.io/laravel-pdf-tutorial-generate-pdf-with-dompdf-in-laravel/

class InformesController extends Controller
{

    public function GetPilotakadakEtaIraupena()
    {
      $festivales = Festival::select(
                      'festivales.id',
                      'festivales.fecha',
                      'festivales.hora',
                      'frontones.name as fronton_name',
                      'municipios.name as fronton_localidad'
                    )
                    ->leftJoin('frontones', 'festivales.fronton_id', 'frontones.id')
                    ->leftJoin('municipios', 'frontones.municipio_id', 'municipios.id')
                    ->get();

      foreach( $festivales as $key => $festival ) {
        $festival->partidos = $this->getPartidosByFestivalId($festival->id);
      }

      $data = array(
        'festivales' => $festivales
      );
      // return view('informes.pilotakadak-eta-iraupena', $data);
      // $pdf = PDF::setPaper('a4', 'landscape')->loadView('informes.pilotakadak-eta-iraupena', $data);
      $pdf = PDF::loadView('informes.pilotakadak-eta-iraupena', $data);
      return $pdf->download('invoice.pdf');
    }

    private function getPartidosByFestivalId($id)
    {
      $partidos = Partido::select(
                    'festival_partidos.id',
                    'festival_partidos.orden',
                    'campeonatos.name as campeonato',
                    'tipo_partidos.name as tipo_partido',
                    'festival_partidos.duracion',
                    'festival_partidos.pelotazos',
                    'festival_partidos.puntos_rojo',
                    'festival_partidos.puntos_azul'
                  )
                  ->where('festival_id', $id)
                  ->leftJoin('campeonatos', 'campeonatos.id', 'festival_partidos.campeonato_id')
                  ->leftJoin('tipo_partidos', 'tipo_partidos.id', 'festival_partidos.tipo_partido_id')
                  ->get();

      foreach( $partidos as $key => $partido ) {
        $partido->pelotaris_rojo = $this->getPelotarisByPartidoId($partido->id, 'R');
        $partido->pelotaris_azul = $this->getPelotarisByPartidoId($partido->id, 'A');
      }

      return $partidos;
    }

    private function getPelotarisByPartidoId($id, $color)
    {
      $pelotaris = Pelotari::select(
                'festival_partido_pelotaris.pelotari_id',
                'festival_partido_pelotaris.asegarce',
                'festival_partido_pelotaris.is_sustituto'
               )
               ->where('festival_partido_id', $id)
               ->where('color', $color)
               ->where('asiste', 1)
               ->get();

      foreach( $pelotaris as $pelotari ) {
        if( $pelotari->asegarce ) {
          $item = PelotariBaiko::where('id', $pelotari->pelotari_id)->first();
        } else {
          $item = PelotariAspe::where('id', $pelotari->pelotari_id)->first();
        }
        $pelotari->alias = ( $item ? $item->alias : 'N/A' );
      }

      return $pelotaris;
    }
}
