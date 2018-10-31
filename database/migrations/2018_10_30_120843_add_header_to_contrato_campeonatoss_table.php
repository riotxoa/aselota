<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeaderToContratoCampeonatossTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_campeonatos', function (Blueprint $table) {
            $table->renameColumn('contrato_id', 'header_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_campeonatos', function (Blueprint $table) {
            $table->renameColumn('header_id', 'contrato_id');
        });
    }
}
