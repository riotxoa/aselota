<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedLesion extends Model
{
    protected $table = 'med_lesiones';

    protected $fillable = [
      'med_parte_id',
      'med_lesion_desc_id',
      'med_partes_cuerpo_id',
      'med_partes_cuerpo_txt',
      'med_medico_id',
      'med_medico_txt',
      'med_causante_id',
      'med_causante_txt',
      'med_grado_lesion_id',
      'med_tipo_atencion_id',
      'descripcion',
      'observaciones'
    ];

    public function parte()
    {
      $this->belongsTo('App\MedParte');
    }
}
