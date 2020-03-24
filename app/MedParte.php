<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedParte extends Model
{
    protected $fillable = [
      'fecha_parte',
      'fecha_accidente',
      'fecha_baja',
      'fecha_alta',
      'fecha_proxima_consulta',
      'fecha_siguiente',
      'pelotari_id',
      'med_diagnostico_id',
      'med_diagnostico_txt',
      'med_centro_id',
      'med_tipo_asistencia_id',
      'med_motivo_alta_id',
      'med_evolucion_id',
      'med_evolucion_txt',
      'med_tratamiento_id',
      'med_tratamiento_txt',
      'is_recaida',
      'is_baja'
    ];

    public function user()
    {
      return $this->belongsTo('App\Pelotari');
    }
}
