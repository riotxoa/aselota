<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_Mesociclo extends Model
{
    protected $table = 'plen_mesociclos';

    protected $fillable = [
      'order',
      'macrociclo_id',
      'tipo_mesociclo_id',
      'fecha_ini',
      'fecha_fin',
      'description',
      'objetivos',
    ];

    public function microciclos() {
      return $this->hasMany('App\PLEN_Microciclo', 'mesociclo_id');
    }

    public function tipo_mesociclo() {
      return $this->belongsTo('App\PLEN_TipoMesociclo');
    }
}
