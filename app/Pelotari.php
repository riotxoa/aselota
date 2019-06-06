<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pelotari extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'DNI',
    'nombre',
    'apellidos',
    'alias',
    'posicion',
    'direccion',
    'cod_postal',
    'municipio_id',
    'provincia_id',
    'email',
    'telefono',
    'num_ss',
    'fecha_nac',
    'telefono_2',
    'telefono_3',
    'iban',
    'num_hijos',
    'promesa'
  ];

  public function contrato() {
    return $this->hasOne('App\Contrato');
  }

  public function comercial() {
    return $this->hasOne('App\ContratoComercial');
  }

  static function get_partidos_jugados_contrato($pelotari_id, $fecha)
  {
    $items = DB::table('festival_partidos as fp')
      ->leftJoin('festival_partido_pelotaris as fpp', 'fp.id', '=', 'fpp.festival_partido_id')
      ->leftJoin('festivales', 'festivales.id', '=', 'fp.festival_id')
      ->leftJoin('contratos', 'contratos.pelotari_id', '=', 'fpp.pelotari_id')
      ->where('fpp.asegarce', '=', 1)
      ->where('fpp.pelotari_id', '=', $pelotari_id)
      ->whereDate('contratos.fecha_ini', '<=', $fecha)
      ->whereDate('contratos.fecha_fin', '>=', $fecha)
      ->whereColumn('contratos.fecha_ini', '<=', 'festivales.fecha')
      ->whereColumn('contratos.fecha_fin', '>=', 'festivales.fecha')
      ->whereNull('contratos.deleted_at')
      ->whereNull('festivales.deleted_at')
      ->where('festivales.estado_id', '=', 3) // Estado = Celebrado
      ->count();

    return $items;
  }
  static function get_partidos_contrato($pelotari_id, $fecha_ini, $fecha_fin, $ini, $fin)
  {
    $items = DB::table('festival_partidos as fp')
      ->leftJoin('festival_partido_pelotaris as fpp', 'fp.id', '=', 'fpp.festival_partido_id')
      ->leftJoin('festivales', 'festivales.id', '=', 'fp.festival_id')
      ->leftJoin('contratos', 'contratos.pelotari_id', '=', 'fpp.pelotari_id')
      ->where('fpp.asegarce', '=', 1)
      ->where('fpp.pelotari_id', '=', $pelotari_id)
      ->whereDate('contratos.fecha_ini', '>=', $fecha_ini)
      ->whereDate('contratos.fecha_fin', '<=', $fecha_fin)
      ->whereColumn('contratos.fecha_ini', '<=', 'festivales.fecha')
      ->whereColumn('contratos.fecha_fin', '>=', 'festivales.fecha')
      ->whereNull('contratos.deleted_at')
      ->whereNull('festivales.deleted_at')
      ->where('festivales.estado_id', '=', 3) // Estado = Celebrado
      ->whereDate('festivales.fecha', '>=', $ini)
      ->whereDate('festivales.fecha', '<=', $fin)
      ->get(); 

    return $items;
  }
  static function get_entrenamientos($pelotari_id, $fecha_ini, $fecha_fin){
    $items = DB::table('entrenamientos as en')
      ->where('en.pelotari_id', '=', $pelotari_id)
      ->whereDate('en.fecha', '>=', $fecha_ini)
      ->whereDate('en.fecha', '<=', $fecha_fin)
      ->get(); 

    return $items;
  }

  static function cuadro_export($fecha_ini, $fecha_fin)
  {
    $items = DB::table('pelotaris as p')
    ->orderBy('id');

    return $items;
  }
}
