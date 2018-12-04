<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrensaEvento extends Model
{
    protected $table = "prensa_eventos";

    protected $fillable = [
      'motivo_id',
      'campeonato_id',
      'provincia_id',
      'municipio_id',
      'desc',
      'fecha',
      'hora',
    ];

    public function pelotaris() {
      return $this->hasMany('App\PrensaPelotari');
    }
}
