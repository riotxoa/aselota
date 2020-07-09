<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2TiemposTrabajoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_tiempos_trabajo')->insert([
          'desc' => '05',
      ]);
      DB::table('med2_tiempos_trabajo')->insert([
          'desc' => '15',
      ]);
      DB::table('med2_tiempos_trabajo')->insert([
          'desc' => '30',
      ]);
      DB::table('med2_tiempos_trabajo')->insert([
          'desc' => '45',
      ]);
      DB::table('med2_tiempos_trabajo')->insert([
          'desc' => '60',
      ]);
    }
}
