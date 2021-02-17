<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlenMesociclosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_mesociclos', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('order')->nullable()->default(0);
            $table->unsignedInteger('macrociclo_id')->nullable();
            $table->unsignedInteger('tipo_mesociclo_id')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('description')->nullable();
            $table->text('objetivos')->nullable();
            $table->timestamps();

            $table->foreign('macrociclo_id')->references('id')->on('plen_macrociclos');
            $table->foreign('tipo_mesociclo_id')->references('id')->on('plen_tipos_mesociclo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_mesociclos');
    }
}
