<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_SesionPelotari extends Model
{
    protected $table = 'plen_sesion_pelotaris';

    protected $fillable = [
      'sesion_id',
      'pelotari_id',
    ];

    public function ejercicios() {
      return $this->belongsToMany('App\PLEN_Ejercicio', 'plen_sesion_ejercicios', 'sesion_pelotari_id', 'ejercicio_id')
              ->select('plen_ejercicios.*', 'plen_sesion_ejercicios.*', 'plen_fases_sesion.desc as fase_desc')
              ->leftJoin('plen_fases_sesion', 'plen_fases_sesion.id', '=', 'plen_sesion_ejercicios.fase_sesion_id')
              ->orderBy('plen_sesion_ejercicios.order', 'ASC');
    }

    public function sesion_ejercicios() {
      return $this->hasMany('App\PLEN_SesionEjercicio', 'sesion_pelotari_id', 'id');
    }
}
