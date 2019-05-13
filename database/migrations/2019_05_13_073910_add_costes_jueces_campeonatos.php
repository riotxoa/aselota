<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostesJuecesCampeonatos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->float('coste_jueces_f')->after('tipo_partido_id')->nullable()->default(0);
            $table->float('coste_jueces_s')->after('tipo_partido_id')->nullable()->default(0);
            $table->float('coste_jueces_p')->after('tipo_partido_id')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->dropColumn('coste_jueces_f');
            $table->dropColumn('coste_jueces_s');
            $table->dropColumn('coste_jueces_p');
        });
    }
}
