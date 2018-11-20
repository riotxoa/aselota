<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaNoGarToContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->float('prima_partido_no_gar')->after('prima_partido')->nullable()->default(0);
            $table->float('prima_manomanista_promo')->after('prima_manomanista')->nullable()->default(0);
            $table->dropColumn('coste');
            $table->dropColumn('coste_no_gar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
          $table->dropColumn('prima_partido_no_gar');
          $table->dropColumn('prima_manomanista_promo');
          $table->float('coste')->after('garantia')->nullable()->default(0);
          $table->float('coste_no_gar')->after('coste')->nullable()->default(0);
        });
    }
}
