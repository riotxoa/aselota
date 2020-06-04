<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nomina extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'pelotari_id',
    'alias',
    'mes',
    'ano',
    'fecha_ini_contrato',
    'partidos_jugados',
    'partidos_garantia',
    'partidos_convocado',
    'partidos_asistencia',
    'estelares',
    'campeonatos',
    'dieta_basica',
    'dieta_partido',
    'coste_partido',
    'coste_partido_2',
    'prima_estelar',
    'prima_manomanista',
    'prima_manomanista_promo',
    'total_dietas',
    'total_estelares',
    'total_partidos',
    'total_torneos',
    'total_pelotari',
    'updated_at'
  ];
}
