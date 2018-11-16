<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratoComercial extends Model
{
    protected $table = 'contratos_comercial';

    protected $fillable = [
      'header_id',
      'fecha_ini',
      'fecha_fin',
      'coste',
    ];
}
