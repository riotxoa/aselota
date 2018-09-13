<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FestivalCoste extends Model
{
  protected $fillable = [
    'festival_id',
    'coste_empresa',
    'importe_venta',
    'aportacion',
    'num_entradas',
    'precio_entradas',
    'num_espectadores',
    'ingreso_taquilla',
    'ingreso_ayto',
    'ingreso_otros',
    'cliente_id',
    'cliente_txt',
  ];
}