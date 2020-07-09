<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Med2MedicosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('med2_medicos')->insert([
          'desc' => 'Iñigo Simón',
      ]);
    }
}
