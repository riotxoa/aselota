<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedLesionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med_lesiones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('med_parte_id');
            $table->unsignedInteger('med_lesion_desc_id');
            $table->unsignedInteger('med_partes_cuerpo_id');
            $table->text('med_partes_cuerpo_txt');
            $table->unsignedInteger('med_medico_id');
            $table->unsignedInteger('med_causante_id');
            $table->text('med_causante_txt');
            $table->unsignedInteger('med_grado_lesion_id');
            $table->unsignedInteger('med_tipo_atencion_id');
            $table->text('descripcion');
            $table->text('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_lesiones');
    }
}
