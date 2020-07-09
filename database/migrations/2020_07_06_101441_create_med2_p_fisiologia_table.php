<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMed2PFisiologiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med2_p_fisiologia', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pelotari_id');
            $table->date('fecha_asistencia')->nullable();
            $table->date('fecha_sintomas')->nullable();
            $table->date('fecha_pe')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('test')->nullable();
            $table->text('observaciones_test')->nullable();
            $table->text('composicion')->nullable();
            $table->text('observaciones_comp')->nullable();
            $table->text('suplementacion')->nullable();
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
        Schema::dropIfExists('med2_p_fisiologia');
    }
}
