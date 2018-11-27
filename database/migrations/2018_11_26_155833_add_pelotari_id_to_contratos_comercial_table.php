<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPelotariIdToContratosComercialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos_comercial', function (Blueprint $table) {
            $table->integer('pelotari_id')->after('header_id')->unsigned()->nullable()->default(null);
            $table->foreign('pelotari_id')->references('id')->on('pelotaris')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos_comercial', function (Blueprint $table) {
            $table->dropColumn('pelotari_id');
        });
    }
}
