<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Festivale extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'fecha',
    'hora',
    'fronton_id',
    'television',
    'television_txt',
    'estado_id',
    'fecha_presu',
  ];

  public function costes() {
    return $this->hasOne('App\FestivalCoste', 'festival_id');
  }

  public function facturacion() {
    return $this->hasOne('App\FestivalFacturacion', 'festival_id');
  }

  public function contactos() {
    return $this->hasOne('App\FestivalContacto', 'festival_id');
  }
}
