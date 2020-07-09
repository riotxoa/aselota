<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Med2PEnfermedad extends Model
{
    protected $table = 'med2_p_enfermedad';

    protected $fillable = [
      'pelotari_id',
      'fecha_asistencia',
      'fecha_inicio',
      'fecha_alta',
      'med2_tipo_asistencia_id',
      'med2_prueba_id',
      'diagnostico_obs',
      'evolucion',
      'tratamiento'
    ];
}
