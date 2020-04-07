<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_TipoMesociclo extends Model
{
    protected $table = 'plen_tipos_mesociclo';

    protected $fillable = [
      'order',
      'desc'
    ];
}
