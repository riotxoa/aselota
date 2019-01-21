<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FestivalContacto extends Model
{
  protected $table = "festival_contactos";

  protected $fillable = [
    'festival_id',
    'contact_01_name',
    'contact_01_desc',
    'contact_01_telephone_1',
    'contact_01_telephone_2',
    'contact_01_email_1',
    'contact_01_email_2',
    'contact_02_name',
    'contact_02_desc',
    'contact_02_telephone_1',
    'contact_02_telephone_2',
    'contact_02_email_1',
    'contact_02_email_2',
    'observaciones',
  ];
}
