<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlenMicrociclosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_microciclos', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('order')->nullable()->default(0);
            $table->unsignedInteger('microciclo_id')->nullable();
            $table->unsignedInteger('tipo_microciclo_id')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->unsignedInteger('volumen')->nullable();
            $table->unsignedInteger('intensidad')->nullable();
            $table->text('description')->nullable();
            $table->text('objetivos')->nullable();
            $table->timestamps();

            $table->foreign('microciclo_id')->references('id')->on('plen_microciclos');
            $table->foreign('tipo_microciclo_id')->references('id')->on('plen_tipos_microciclo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_microciclos');
    }
}
