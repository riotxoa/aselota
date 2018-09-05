<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FestivalPartidoMarcadore extends Model
{
  protected $table = "festival_partido_marcadores";

  protected $fillable = [
    'festival_partido_id',
    'order',
    'marcador_rojo',
    'marcador_azul',
  ];
}
