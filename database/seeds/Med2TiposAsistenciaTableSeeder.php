<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2TiposAsistenciaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_tipos_asistencia')->insert([
          'desc' => 'Ambulatoria',
      ]);
      DB::table('med2_tipos_asistencia')->insert([
          'desc' => 'Hospitalaria',
      ]);
    }
}
