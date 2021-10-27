<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_SesionEjercicio extends Model
{
    protected $table = 'plen_sesion_ejercicios';

    protected $fillable = [
      'sesion_pelotari_id',
      'order',
      'fase_sesion_id',
      'ejercicio_id',
      'volumen',
      'intensidad'
    ];
}
