<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FestivalPartidoPelotari extends Model
{
  protected $fillable = [
    'festival_partido_id',
    'color',
    'posicion',
    'pelotari_id',
    'asegarce',
    'asiste',
    'is_sustituto',
    'sustituto_id',
    'sustituto_txt',
    'observaciones'
  ];

  public function pelotari() {
    return $this->belongsTo('App\Pelotari');
  }
}
