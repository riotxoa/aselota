<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedPartesDeltaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med_partes_delta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('med_parte_id');
            $table->date('fecha_recaida')->nullable();
            $table->date('fecha_accidente_recaida')->nullable();
            $table->unsignedInteger('fronton_id')->nullable();
            $table->time('hora_at')->nullable();
            $table->unsignedInteger('med_lesion_id')->nullable();
            $table->text('med_lesion_txt')->nullable();
            $table->unsignedInteger('med_parte_cuerpo_id')->nullable();
            $table->unsignedInteger('med_medico_id')->nullable();
            $table->text('med_medico_txt')->nullable();
            $table->unsignedInteger('med_tiempo_trabajo_id')->nullable();
            $table->text('desc_accidente')->nullable();
            $table->unsignedInteger('med_causante_id')->nullable();
            $table->text('med_causante_txt')->nullable();
            $table->unsignedInteger('med_grado_lesion_id')->nullable();
            $table->unsignedInteger('med_tipo_atencion_id')->nullable();
            $table->text('tiempo_previsto')->nullable();
            $table->text('tiempo_previsto_txt')->nullable();
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
        Schema::dropIfExists('med_partes_delta');
    }
}
