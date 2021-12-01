<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlenSesionPelotarisTableToDeleteForeignPelotariId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('plen_sesion_pelotaris', function (Blueprint $table) {
        //     $table->dropForeign(['pelotari_id']);
        //     $table->dropIndex('plen_sesion_pelotaris_pelotari_id_foreign');
        //     $table->dropForeign(['sesion_id']);
        //     $table->dropIndex('plen_sesion_pelotaris_sesion_id_foreign');
        // });
        Schema::table('plen_sesion_ejercicios', function (Blueprint $table) {
            $table->dropForeign(['sesion_pelotari_id']);
            $table->dropForeign(['fase_sesion_id']);
            $table->dropForeign(['ejercicio_id']);

            $table->dropIndex('plen_sesion_ejercicios_sesion_pelotari_id_foreign');
            $table->dropIndex('plen_sesion_ejercicios_fase_sesion_id_foreign');
            $table->dropIndex('plen_sesion_ejercicios_ejercicio_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('plen_sesion_pelotaris', function (Blueprint $table) {
        //     $table->foreign('sesion_id')->references('id')->on('plen_sesiones');
        //     $table->foreign('pelotari_id')->references('id')->on('pelotaris');
        // });
        Schema::table('plen_sesion_pelotaris', function (Blueprint $table) {
          $table->foreign('sesion_pelotari_id')->references('id')->on('plen_sesion_pelotaris');
          $table->foreign('fase_sesion_id')->references('id')->on('plen_fases_sesion');
          $table->foreign('ejercicio_id')->references('id')->on('plen_ejercicios');
        });
    }
}
