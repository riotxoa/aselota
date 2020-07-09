<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2CausantesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_causantes')->insert([
          'desc' => 'Pelota',
      ]);
      DB::table('med2_causantes')->insert([
          'desc' => 'Pared de frontón',
      ]);
      DB::table('med2_causantes')->insert([
          'desc' => 'Suelo de frontón',
      ]);
      DB::table('med2_causantes')->insert([
          'desc' => 'Contrario',
      ]);
      DB::table('med2_causantes')->insert([
          'desc' => 'Máquina gimnasio',
      ]);
    }
}
