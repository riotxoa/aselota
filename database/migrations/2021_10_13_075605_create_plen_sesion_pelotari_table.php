<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlenSesionPelotariTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_sesion_pelotaris', function (Blueprint $table) {
            $table->unsignedInteger('sesion_id')->nullable();
            $table->unsignedInteger('pelotari_id')->nullabel();

            $table->foreign('sesion_id')->references('id')->on('plen_sesiones');
            $table->foreign('pelotari_id')->references('id')->on('pelotaris');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_sesion_pelotaris');
    }
}
