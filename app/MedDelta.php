<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedDelta extends Model
{
    protected $table = 'med_partes_delta';

    protected $fillable = [
      'med_parte_id',
      'fecha_recaida',
      'fecha_accidente_recaida',
      'fronton_id',
      'hora_at',
      'med_lesion_id',
      'med_lesion_txt',
      'med_parte_cuerpo_id',
      'med_medico_id',
      'med_medico_txt',
      'med_tiempo_trabajo_id',
      'desc_accidente',
      'med_causante_id',
      'med_causante_txt',
      'med_grado_lesion_id',
      'med_tipo_atencion_id',
      'tiempo_previsto',
      'tiempo_previsto_txt'
    ];

    public function parte()
    {
      $this->belongsTo('App\MedParte');
    }
}
