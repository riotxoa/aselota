<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMed2PEnfermedadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med2_p_enfermedad', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pelotari_id');
            $table->date('fecha_asistencia')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_alta')->nullable();
            $table->unsignedInteger('med2_tipo_asistencia_id')->nullable();
            $table->unsignedInteger('med2_prueba_id')->nullable();
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
        Schema::dropIfExists('med2_p_enfermedad');
    }
}
