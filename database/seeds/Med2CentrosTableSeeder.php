<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2CentrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_centros')->insert([
          'desc' => 'Hospital',
      ]);
      DB::table('med2_centros')->insert([
          'desc' => 'Centro de salud',
      ]);
      DB::table('med2_centros')->insert([
          'desc' => 'Mutua de accidentes',
      ]);
      DB::table('med2_centros')->insert([
          'desc' => 'Consulta',
      ]);
      DB::table('med2_centros')->insert([
          'desc' => 'Frontón',
      ]);
    }
}
