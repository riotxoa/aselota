<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2PruebasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_pruebas')->insert([
          'desc' => 'Rx',
      ]);
      DB::table('med2_pruebas')->insert([
          'desc' => 'RMN',
      ]);
      DB::table('med2_pruebas')->insert([
          'desc' => 'TAC',
      ]);
      DB::table('med2_pruebas')->insert([
          'desc' => 'Ecografía',
      ]);
    }
}
