<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_TipoEjercicio extends Model
{
    protected $table = 'plen_tipos_ejercicio';

    protected $fillable = [
      'order',
      'desc'
    ];
}
