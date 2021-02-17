<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PLEN_Sesion extends Model
{
    protected $table = 'plen_sesiones';

    protected $fillable = [
      'order',
      'fecha',
      'hora',
      'fronton_id',
    ];

    public function fronton() {
      return $this->belongsTo('App\Fronton');
    }
}
