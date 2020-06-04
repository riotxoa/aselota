<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pelotari_id');
            $table->string('alias');
            $table->tinyInteger('mes');
            $table->smallInteger('ano');
            $table->date('fecha_ini_contrato');
            $table->smallInteger('partidos_jugados')->nullable()->default(0);
            $table->smallInteger('partidos_garantia')->nullable()->default(0);
            $table->tinyInteger('partidos_convocado')->nullable()->default(0);
            $table->tinyInteger('partidos_asistencia')->nullable()->default(0);
            $table->tinyInteger('estelares')->nullable()->default(0);
            $table->text('campeonatos')->nullable();
            $table->float('dieta_basica')->nullable()->default(0);
            $table->float('dieta_partido')->nullable()->default(0);
            $table->float('coste_partido')->nullable()->default(0);
            $table->float('coste_partido_2')->nullable()->default(0);
            $table->float('prima_estelar')->nullable()->default(0);
            $table->float('prima_manomanista')->nullable()->default(0);
            $table->float('prima_manomanista_promo')->nullable()->default(0);
            $table->float('total_dietas')->nullable()->default(0);
            $table->float('total_estelares')->nullable()->default(0);
            $table->float('total_partidos')->nullable()->default(0);
            $table->float('total_torneos')->nullable()->default(0);
            $table->float('total_pelotari')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominas');
    }
}
