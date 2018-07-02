<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Derecho extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'pelotari_id',
    'contrato_id',
    'fecha_ini',
    'fecha_fin',
    'amount',
  ];
}
