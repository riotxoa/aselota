<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlenSesionPelotarissTable2AddIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plen_sesion_pelotaris', function (Blueprint $table) {
            $table->increments('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plen_sesion_pelotaris', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
