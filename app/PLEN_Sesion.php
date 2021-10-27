<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class PLEN_Sesion extends Model
{
    protected $table = 'plen_sesiones';

    protected $fillable = [
      'order',
      'microciclo_id',
      'fecha',
      'hora',
      'fronton_id',
    ];

    public function fronton() {
      return $this->belongsTo('App\Fronton');
    }

    public function pelotaris() {
      return $this->belongsToMany('App\Pelotari', 'plen_sesion_pelotaris', 'sesion_id', 'pelotari_id');
    }

    public function sesion_pelotaris() {
      return $this->hasMany('App\PLEN_SesionPelotari', 'sesion_id', 'id');
    }

    public function getEjercicios() {
      $sesion_pelotari_ids = PLEN_SesionPelotari::select('id')->where('sesion_id', $this->id)->pluck('id');
      $ejercicio_ids = DB::table('plen_sesion_ejercicios')->whereIn('sesion_pelotari_id', $sesion_pelotari_ids)->pluck('ejercicio_id');
      return PLEN_Ejercicio::whereIn('id', $ejercicio_ids)->get();
    }
}
