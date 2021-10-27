<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlenSesionEjerciciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_sesion_ejercicios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sesion_pelotari_id');
            $table->unsignedInteger('order')->nullable();
            $table->unsignedInteger('fase_sesion_id')->nullable();
            $table->unsignedInteger('ejercicio_id')->nullable();
            $table->unsignedInteger('volumen')->nullable();
            $table->unsignedInteger('intensidad')->nullable();
            $table->timestamps();

            $table->foreign('sesion_pelotari_id')->references('id')->on('plen_sesion_pelotaris');
            $table->foreign('fase_sesion_id')->references('id')->on('plen_fases_sesion');
            $table->foreign('ejercicio_id')->references('id')->on('plen_ejercicios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_sesion_ejercicios');
    }
}
