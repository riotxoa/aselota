<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelotari extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'DNI',
    'nombre',
    'apellidos',
    'alias',
    'posicion',
    'direccion',
    'cod_postal',
    'municipio_id',
    'provincia_id',
    'email',
    'telefono',
    'num_ss',
    'fecha_nac',
    'telefono_2',
    'telefono_3',
    'iban',
    'num_hijos',
  ];

  public function contrato() {
    return $this->hasOne('App\Contrato');
  }
}
