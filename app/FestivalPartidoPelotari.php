<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FestivalPartidoPelotari extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'festival_partido_id',
    'color',
    'posicion',
    'pelotari_id',
  ];

  public function pelotari() {
    return $this->belongsTo('App\Pelotari');
  }
}
