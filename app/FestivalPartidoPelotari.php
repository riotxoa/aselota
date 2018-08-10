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
  ];

  public function pelotari() {
    return $this->belongsTo('App\Pelotari');
  }
}
