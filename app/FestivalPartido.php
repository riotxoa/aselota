<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FestivalPartido extends Model
{
  protected $fillable = [
    'festival_id',
    'orden',
    'estelar',
    'campeonato_id',
    'tipo_partido_id',
    'fase',
    'duracion',
    'pelotazos',
    'obs_publico',
    'obs_fronton',
    'obs_incidencias',
    'obs_observaciones',
    'puntos_rojo',
    'puntos_azul',
  ];
}
