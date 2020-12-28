<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Med2InformesPEnf extends Model
{
    protected $table = 'med2_informes_p_enf';

    protected $fillable = [
      'name',
      'desc',
      'path',
      'parte_id',
    ];

    public function parte()
    {
      return $this->belongsTo(Med2PEnfermedad::class);
    }

}
