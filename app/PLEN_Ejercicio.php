<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_Ejercicio extends Model
{
    protected $table = 'plen_ejercicios';

    protected $fillable = [
      'order',
      'name',
      'desc',
      'tipo_ejercicio_id',
      'subtipo_ejercicio_id',
      'imagen',
      'video',
      'material',
    ];

    public function tipo_ejercicio() {
      return $this->belongsTo('App\PLEN_TipoEjercicio');
    }

    public function subtipo_ejercicio() {
      return $this->belongsTo('App\PLEN_SubtipoEjercicio');
    }
}
