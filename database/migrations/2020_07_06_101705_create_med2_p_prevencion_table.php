<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMed2PPrevencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med2_p_prevencion', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pelotari_id');
            $table->date('fecha_asistencia')->nullable();
            $table->text('historia')->nullable();
            $table->text('reconocimiento')->nullable();
            $table->date('fecha_rec')->nullable();
            $table->text('analiticas')->nullable();
            $table->date('fecha_ana')->nullable();
            $table->text('pruebas')->nullable();
            $table->date('fecha_pru')->nullable();
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
        Schema::dropIfExists('med2_p_prevencion');
    }
}
