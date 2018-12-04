<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrensaPelotari extends Model
{
    protected $table = "prensa_evento_pelotaris";

    protected $fillable = [
      'prensa_evento_id',
      'pelotari_id',
      'asiste',
      'motivo',
    ];

    public function evento() {
      return $this->belongsTo('App\PrensaEvento');
    }
}
