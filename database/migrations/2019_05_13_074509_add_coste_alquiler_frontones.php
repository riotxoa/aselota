<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCosteAlquilerFrontones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontones', function (Blueprint $table) {
            $table->float('coste_alquiler')->after('telefono')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frontones', function (Blueprint $table) {
            $table->dropColumn('coste_alquiler');
            $table->dropColumn('coste_jueces_s');
            $table->dropColumn('coste_jueces_p');
        });
    }
}
