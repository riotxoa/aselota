<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;

class PLEN_Sesion extends Model
{
    protected $table = 'plen_sesiones';

    protected $fillable = [
      'order',
      'microciclo_id',
      'fecha',
      'hora',
      'fronton_id',
    ];

    public function fronton() {
      return $this->belongsTo('App\Fronton');
    }

    public function pelotaris() {
      return $this->belongsToMany('App\Pelotari', 'plen_sesion_pelotaris', 'sesion_id', 'pelotari_id');
    }
}
