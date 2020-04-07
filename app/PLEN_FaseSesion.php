<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_FaseSesion extends Model
{
    protected $table = 'plen_fases_sesion';

    protected $fillable = [
      'order',
      'desc'
    ];
}
