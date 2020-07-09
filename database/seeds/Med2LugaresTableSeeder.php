<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2LugaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_lugares')->insert([
          'desc' => 'Beasain',
      ]);
      DB::table('med2_lugares')->insert([
          'desc' => 'Bilbao',
      ]);
      DB::table('med2_lugares')->insert([
          'desc' => 'Donostia',
      ]);
      DB::table('med2_lugares')->insert([
          'desc' => 'Eibar',
      ]);
      DB::table('med2_lugares')->insert([
          'desc' => 'Logroño',
      ]);
      DB::table('med2_lugares')->insert([
          'desc' => 'Pamplona',
      ]);
      DB::table('med2_lugares')->insert([
          'desc' => 'Tolosa',
      ]);
    }
}
