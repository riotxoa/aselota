<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'pelotari_id',
    'fecha_ini',
    'fecha_fin',
    'dieta_mes',
    'dieta_partido',
    'prima_partido',
    'prima_estelar',
    'prima_manomanista',
    'garantia',
    'coste',
    'formacion',
    'd_imagen',
  ];
}
