<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Med2PFisiologia extends Model
{
    protected $table = 'med2_p_fisiologia';

    protected $fillable = [
      'pelotari_id',
      'fecha_asistencia',
      'fecha_sintomas',
      'fecha_pe',
      'observaciones',
      'test',
      'observaciones_test',
      'composicion',
      'observaciones_comp',
      'suplementacion'
    ];
}
