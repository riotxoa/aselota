<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlenTiposMesociclo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plen_tipos_mesociclo', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('order')->nullable()->default(0);
            $table->char('desc',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plen_tipos_mesociclo');
    }
}
