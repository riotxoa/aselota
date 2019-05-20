<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrenamiento extends Model
{
  protected $fillable = [
    'pelotari_id',
    'provincia_id',
    'municipio_id',
    'asistencia',
    'duracion',
    'fecha',
    'hora',
    'contenido_id',
    'fronton_id',
    'pre_entreno',
    'contenido',
    'post_entreno',
    'actitud_id',
    'aprovechamiento_id',
    'evolucion_id',
    'comentarios',
    'promesa'
  ];
}
