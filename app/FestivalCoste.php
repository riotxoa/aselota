<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FestivalCoste extends Model
{
  protected $fillable = [
    'festival_id',
    'coste_empresa',
    'sanidad',
    'num_auxiliares',
    'num_taquilleros',
    'importe_venta',
    'aportacion',
    'num_espectadores',
    'ingreso_taquilla',
    'ingreso_ayto',
    'ingreso_otros',
    'cliente_id',
    'cliente_txt',
    'porcentaje',
  ];
}
