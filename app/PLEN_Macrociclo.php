<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_Macrociclo extends Model
{
    protected $table = 'plen_macrociclos';

    protected $fillable = [
      'order',
      'fecha_ini',
      'fecha_fin',
      'description',
      'objetivos'
    ];

    public function mesociclos() {
      return $this->hasMany('App\PLEN_Mesociclo');
    }
}
