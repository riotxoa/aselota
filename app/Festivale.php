<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Festivale extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'fecha',
    'hora',
    'fronton_id',
    'television',
    'television_txt',
    'estado_id',
    'fecha_presu',
  ];
}
