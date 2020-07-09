<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Med2PDelta extends Model
{
    protected $table = 'med2_p_delta';

    protected $fillable = [
      'p_accidente_id',
      'fecha_accidente',
      'fecha_baja',
      'fecha_recaida',
      'fecha_accidente_rec',
      'med2_lugar_id',
      'med2_causante_id',
      'med2_tiempo_trabajo_id',
      'parte_cuerpo',
      'descripcion_lesion',
      'med2_grado_lesion_id',
      'med2_medico_id',
      'med2_medico_txt',
      'med2_tipo_asistencia_id',
      'hora_at',
      'tiempo_previsto'
    ];
}
