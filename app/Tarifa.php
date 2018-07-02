<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarifa extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'pelotari_id',
    'campeonato_id',
    'fecha_ini',
    'fecha_fin',
    'campeon',
    'subcampeon',
    'liga_semifinal',
    'liga_cuartos',
    'semifinal',
    'cuartos',
    'octavos'
  ];
}
