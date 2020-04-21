<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExplotacionIdField2FestivalFacturacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('festival_facturacion', function (Blueprint $table) {
            $table->unsignedInteger('explotacion_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('festival_facturacion', function (Blueprint $table) {
            $table->dropColumn('explotacion_id');
        });
    }
}
