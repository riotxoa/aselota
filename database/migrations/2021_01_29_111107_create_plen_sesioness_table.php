<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlenSesionessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_sesiones', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('order')->nullable()->default(0);
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->unsignedInteger('fronton_id')->nullable();
            $table->timestamps();

            $table->foreign('fronton_id')->references('id')->on('frontones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_sesiones');
    }
}
