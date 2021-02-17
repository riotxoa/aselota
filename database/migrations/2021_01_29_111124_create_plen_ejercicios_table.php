<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlenEjerciciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_ejercicios', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('order')->nullable()->default(0);
            $table->string('name')->nullable();
            $table->text('desc')->nullable();
            $table->unsignedInteger('tipo_ejercicio_id')->nullable();
            $table->unsignedInteger('subtipo_ejercicio_id')->nullable();
            $table->text('imagen')->nullable();
            $table->text('video')->nullable();
            $table->text('material')->nullable();
            $table->timestamps();

            $table->foreign('tipo_ejercicio_id')->references('id')->on('plen_tipos_ejercicio');
            $table->foreign('subtipo_ejercicio_id')->references('id')->on('plen_subtipos_ejercicio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_ejercicios');
    }
}
