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
    'sustituto_asegarce',
    'sustituto_txt',
    'observaciones'
  ];

  public function pelotari() {
    return $this->belongsTo('App\Pelotari');
  }

  public function comercial() {
    $pelotari = Pelotari::find($this->pelotari_id);
    return $pelotari->comercial();
  }

  public function festival() {
    $partido = FestivalPartido::find($this->festival_partido_id);
    return $partido->festival();
  }
}
