<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrenamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->char('hora',5);
            $table->integer('pelotari_id')->unsigned()->nullable()->default(null);
            $table->foreign('pelotari_id')->references('id')->on('pelotaris')->onUpdate('cascade')->onDelete('set null');
            $table->integer('provincia_id')->unsigned()->nullable()->default(null);
            $table->foreign('provincia_id')->references('id')->on('provincias')->onUpdate('cascade')->onDelete('set null');
            $table->integer('municipio_id')->unsigned()->nullable()->default(null);
            $table->foreign('municipio_id')->references('id')->on('municipios')->onUpdate('cascade')->onDelete('set null');
            $table->integer('contenido_id')->unsigned()->nullable()->default(null);
            $table->foreign('contenido_id')->references('id')->on('entr_contenido')->onUpdate('cascade')->onDelete('set null');
            $table->boolean('asistencia');
            $table->char('duracion',3);
            $table->text('motivo');
            $table->text('pre_entreno');
            $table->text('contenido');
            $table->text('post_entreno');
            $table->integer('actitud_id')->unsigned()->nullable()->default(null);
            $table->foreign('actitud_id')->references('id')->on('entr_actitud')->onUpdate('cascade')->onDelete('set null');
            $table->integer('aprovechamiento_id')->unsigned()->nullable()->default(null);
            $table->foreign('aprovechamiento_id')->references('id')->on('entr_aprovechamiento')->onUpdate('cascade')->onDelete('set null');
            $table->integer('evolucion_id')->unsigned()->nullable()->default(null);
            $table->foreign('evolucion_id')->references('id')->on('entr_evolucion')->onUpdate('cascade')->onDelete('set null');
            $table->text('comentarios');
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
        Schema::dropIfExists('entrenamientos');
    }
}
