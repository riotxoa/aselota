<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorToPlenTiposMesocicloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plen_tipos_mesociclo', function (Blueprint $table) {
            $table->string('color', 7)->after('desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plen_tipos_mesociclo', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
