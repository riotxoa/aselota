<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_Microciclo extends Model
{
    protected $table = 'plen_microciclos';

    protected $fillable = [
      'order',
      'mesociclo_id',
      'tipo_microciclo_id',
      'fecha_ini',
      'fecha_fin',
      'volumen',
      'intensidad',
      'description',
      'objetivos',
    ];

    public function sesiones() {
      return $this->hasMany('App\PLEN_Sesion', 'microciclo_id');
    }

    public function tipo_microciclo() {
      return $this->belongsTo('App\PLEN_TipoMicrociclo');
    }
}
