<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FestivalPartido extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'festival_id',
    'orden',
    'estelar',
    'campeonato_id',
    'tipo_partido_id',
    'fase',
  ];
}
