<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2GradosLesionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_grados_lesion')->insert([
          'desc' => 'Leve',
      ]);
      DB::table('med2_grados_lesion')->insert([
          'desc' => 'Grave',
      ]);
      DB::table('med2_grados_lesion')->insert([
          'desc' => 'Muy grave',
      ]);
    }
}
