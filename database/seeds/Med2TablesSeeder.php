<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class Med2TablesSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->seedersPath = database_path('seeds').'/';
      $this->seed('Med2CausantesTableSeeder');
      $this->seed('Med2CentrosTableSeeder');
      $this->seed('Med2GradosLesionTableSeeder');
      $this->seed('Med2LugaresTableSeeder');
      $this->seed('Med2MedicosTableSeeder');
      $this->seed('Med2PruebasTableSeeder');
      $this->seed('Med2TiemposTrabajoTableSeeder');
      $this->seed('Med2TiposAsistenciaTableSeeder');
    }
}
