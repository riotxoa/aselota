<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Med2PPrevencion extends Model
{
    protected $table = 'med2_p_prevencion';

    protected $fillable = [
      'pelotari_id',
      'fecha_asistencia',
      'historia',
      'reconocimiento',
      'fecha_rec',
      'analiticas',
      'fecha_ana',
      'pruebas',
      'fecha_pru'
    ];
}
