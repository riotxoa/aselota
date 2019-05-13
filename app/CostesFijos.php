<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CostesFijos extends Model
{
    protected $fillable = [
      'id',
      'descripcion',
      'coste'
    ];
  }
  
