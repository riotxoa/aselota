<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_SubtipoEjercicio extends Model
{
    protected $table = 'plen_subtipos_ejercicio';

    protected $fillable = [
      'plen_tipos_ejercicio_id',
      'order',
      'desc'
    ];
}
