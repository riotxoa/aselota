<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Pelotari extends Model
{
  protected $fillable = [
    'DNI', 'nombre', 'apellidos', 'alias', 'posicion', 'direccion', 'cod_postal', 'municipio_id', 'provincia_id', 'email', 'telefono'
  ];
}
