<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosComercial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos_comercial', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('header_id')->unsigned()->nullable()->default(null);
            $table->foreign('header_id')->references('id')->on('contratos_header')->onUpdate('cascade')->onDelete('cascade');
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->float('coste');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos_comercial');
    }
}
