<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratoHeader extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'pelotari_id',
    'fecha_ini',
    'fecha_fin',
    'disabled',
    'observaciones',
  ];

  protected $table = 'contratos_header';
}
