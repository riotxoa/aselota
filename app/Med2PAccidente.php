<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Med2PAccidente extends Model
{
    protected $table = 'med2_p_accidente';

    protected $fillable = [
      'pelotari_id',
      'fecha_parte',
      'fecha_accidente',
      'is_recaida',
      'is_baja',
      'med2_centro_id',
      'med2_tipo_asistencia_id',
      'med2_causante_id',
      'med2_prueba_id',
      'parte_cuerpo',
      'diagnostico_ini',
      'diagnostico_obs',
      'evolucion',
      'tratamiento'
    ];

    public function informes()
    {
      return $this->hasMany(Med2InformesPAcc::class, 'parte_id');
    }
}
