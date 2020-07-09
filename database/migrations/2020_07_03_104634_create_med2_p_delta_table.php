<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMed2PDeltaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med2_p_delta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('p_accidente_id');
            $table->date('fecha_accidente')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->date('fecha_recaida')->nullable();
            $table->date('fecha_accidente_rec')->nullable();
            $table->unsignedInteger('med2_lugar_id')->nullable();
            $table->unsignedInteger('med2_causante_id')->nullable();
            $table->unsignedInteger('med2_tiempo_trabajo_id')->nullable();
            $table->text('parte_cuerpo')->nullable();
            $table->text('descripcion_lesion')->nullable();
            $table->unsignedInteger('med2_grado_lesion_id')->nullable();
            $table->unsignedInteger('med2_medico_id')->nullable();
            $table->text('med2_medico_txt')->nullable();
            $table->unsignedInteger('med2_tipo_asistencia_id')->nullable();
            $table->time('hora_at')->nullable();
            $table->text('tiempo_previsto')->nullable();
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
        Schema::dropIfExists('med2_p_delta');
    }
}
