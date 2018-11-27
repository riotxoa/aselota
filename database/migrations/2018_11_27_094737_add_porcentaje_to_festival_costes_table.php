<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPorcentajeToFestivalCostesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('festival_costes', function (Blueprint $table) {
            $table->decimal('porcentaje', 3, 0)->after('cliente_txt')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('festival_costes', function (Blueprint $table) {
            $table->dropColumn('porcentaje');
        });
    }
}
