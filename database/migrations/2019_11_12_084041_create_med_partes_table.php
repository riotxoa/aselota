<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedPartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med_partes', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_parte');
            $table->date('fecha_accidente')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_proxima_consulta')->nullable();
            $table->date('fecha_siguiente')->nullable();
            $table->unsignedInteger('pelotari_id');
            $table->unsignedInteger('med_diagnostico_id')->nullable();
            $table->text('med_diagnostico_txt')->nullable();
            $table->unsignedInteger('med_centro_id')->nullable();
            $table->unsignedInteger('med_tipo_asistencia_id')->nullable();
            $table->unsignedInteger('med_motivo_alta_id')->nullable();
            $table->unsignedInteger('med_evolucion_id')->nullable();
            $table->text('med_evolucion_txt')->nullable();
            $table->unsignedInteger('med_tratamiento_id')->nullable();
            $table->text('med_tratamiento_txt')->nullable();
            $table->boolean('is_recaida')->default(false);
            $table->boolean('is_baja')->default(false);
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
        Schema::dropIfExists('med_partes');
    }
}
