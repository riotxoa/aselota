<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelotarisAspe extends Model
{
    use SoftDeletes;
    
    protected $table = "pelotaris_aspe";
}
