<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_TipoMicrociclo extends Model
{
    protected $table = 'plen_tipos_microciclo';

    protected $fillable = [
      'order',
      'desc'
    ];
}
