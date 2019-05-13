<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDesgloseCostesToFestivalCostesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('festival_costes', function (Blueprint $table) {
            $table->float('coste_pelotaris')->after('id')->nullable()->default(0);
            $table->float('coste_jueces')->after('coste_pelotaris')->nullable()->default(0);
            $table->float('coste_cancha')->after('coste_jueces')->nullable()->default(0);
            $table->float('coste_material')->after('coste_cancha')->nullable()->default(0);
            $table->float('coste_auxiliares')->after('coste_material')->nullable()->default(0);
            $table->float('coste_taquillera')->after('coste_auxiliares')->nullable()->default(0);
            $table->float('coste_sanidad')->after('coste_taquillera')->nullable()->default(0);
            $table->integer('num_auxiliares')->after('updated_at')->nullable()->default(1);
            $table->integer('num_taquilleros')->after('num_auxiliares')->nullable()->default(0);
            $table->float('sanidad')->after('num_taquilleros')->nullable()->default(0);
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
            $table->dropColumn('coste_pelotaris');
            $table->dropColumn('coste_jueces');
            $table->dropColumn('coste_cancha');
            $table->dropColumn('coste_material');
            $table->dropColumn('coste_auxiliares');
            $table->dropColumn('coste_taquillera');
            $table->dropColumn('coste_sanidad');
            $table->dropColumn('num_auxiliares');
            $table->dropColumn('num_taquilleros');
            $table->dropColumn('sanidad');
        });
    }
}
