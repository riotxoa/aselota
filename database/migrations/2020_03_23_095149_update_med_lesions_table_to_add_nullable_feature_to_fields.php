<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMedLesionsTableToAddNullableFeatureToFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('med_lesiones', function (Blueprint $table) {
          $table->unsignedInteger('med_lesion_desc_id')->nullable()->change();
          $table->unsignedInteger('med_partes_cuerpo_id')->nullable()->change();
          $table->text('med_partes_cuerpo_txt')->nullable()->change();
          $table->unsignedInteger('med_medico_id')->nullable()->change();
          $table->unsignedInteger('med_causante_id')->nullable()->change();
          $table->text('med_causante_txt')->nullable()->change();
          $table->unsignedInteger('med_grado_lesion_id')->nullable()->change();
          $table->unsignedInteger('med_tipo_atencion_id')->nullable()->change();
          $table->text('descripcion')->nullable()->change();
          $table->text('observaciones')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_lesiones', function (Blueprint $table) {
          $table->unsignedInteger('med_lesion_desc_id')->nullable(false)->change();
          $table->unsignedInteger('med_partes_cuerpo_id')->nullable(false)->change();
          $table->text('med_partes_cuerpo_txt')->nullable(false)->change();
          $table->unsignedInteger('med_medico_id')->nullable(false)->change();
          $table->unsignedInteger('med_causante_id')->nullable(false)->change();
          $table->text('med_causante_txt')->nullable(false)->change();
          $table->unsignedInteger('med_grado_lesion_id')->nullable(false)->change();
          $table->unsignedInteger('med_tipo_atencion_id')->nullable(false)->change();
          $table->text('descripcion')->nullable(false)->change();
          $table->text('observaciones')->nullable(false)->change();
        });
    }
}
