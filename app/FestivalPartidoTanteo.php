<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FestivalPartidoTanteo extends Model
{
  protected $table = "festival_partido_tanteo";

  protected $fillable = [
    'festival_partido_id',
    'pelotari_id',
    'tanteo_order',
    'tanteo_desc',
    'tanteo',
  ];
}
