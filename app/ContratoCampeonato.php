<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratoCampeonato extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'pelotari_id',
    'header_id',
    'campeonato_id',
    'campeon',
    'subcampeon',
    'liga_semifinal',
    'liga_cuartos',
    'semifinal',
    'cuartos',
    'octavos',
    'dieciseisavos',
    'treintaidosavos',
  ];
}
