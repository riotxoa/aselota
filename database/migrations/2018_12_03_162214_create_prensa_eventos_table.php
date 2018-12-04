<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrensaEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prensa_eventos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('motivo_id')->unsigned()->nullable()->default(null);
            $table->foreign('motivo_id')->references('id')->on('prensa_motivos')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('campeonato_id')->unsigned()->nullable()->default(null);
            $table->foreign('campeonato_id')->references('id')->on('campeonatos')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('provincia_id')->unsigned()->nullable()->default(null);
            $table->foreign('provincia_id')->references('id')->on('provincias')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('municipio_id')->unsigned()->nullable()->default(null);
            $table->foreign('municipio_id')->references('id')->on('municipios')->onUpdate('cascade')->onDelete('cascade');
            $table->date('fecha');
            $table->char('hora',5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prensa_eventos');
    }
}
