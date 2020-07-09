<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMed2PAccidenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med2_p_accidente', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('pelotari_id');
          $table->date('fecha_parte')->nullable();
          $table->date('fecha_accidente')->nullable();
          $table->boolean('is_recaida')->default(false);
          $table->boolean('is_baja')->default(false);
          $table->unsignedInteger('med2_centro_id')->nullable();
          $table->unsignedInteger('med2_tipo_asistencia_id')->nullable();
          $table->unsignedInteger('med2_causante_id')->nullable();
          $table->unsignedInteger('med2_prueba_id')->nullable();
          $table->text('parte_cuerpo')->nullable();
          $table->text('diagnostico_ini')->nullable();
          $table->text('diagnostico_obs')->nullable();
          $table->text('evolucion')->nullable();
          $table->text('tratamiento')->nullable();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med2_p_accidente');
    }
}
