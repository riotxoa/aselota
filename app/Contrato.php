<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'header_id',
    'pelotari_id',
    'fecha_ini',
    'fecha_fin',
    'ficha',
    'sueldo',
    'dieta_mes',
    'dieta_partido',
    'prima_partido',
    'prima_partido_no_gar',
    'prima_estelar',
    'prima_manomanista',
    'prima_manomanista_promo',
    'garantia',
    'formacion',
    'd_imagen',
  ];
}
