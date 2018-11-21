<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FestivalCosteEntradas extends Model
{
  protected $table = 'festival_costes_entradas';

  protected $fillable = [
    'festival_id',
    'name',
    'amount',
    'price',
  ];
}
