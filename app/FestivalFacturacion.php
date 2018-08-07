<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FestivalFacturacion extends Model
{
  protected $table = "festival_facturacion";
  
  protected $fillable = [
    'festival_id',
    'fpago_id',
    'fecha',
    'importe',
    'enviar_id',
    'observaciones',
    'pagado',
    'seguimiento',
  ];
}
