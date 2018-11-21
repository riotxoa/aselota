<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFestivalCostesEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festival_costes_entradas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('festival_id')->unsigned()->nullable()->default(null);
            $table->foreign('festival_id')->references('id')->on('festivales')->onUpdate('cascade')->onDelete('cascade');
            $table->char('name',100);
            $table->integer('amount');
            $table->float('price');
        });

        Schema::table('festival_costes', function (Blueprint $table) {
          $table->dropColumn('num_entradas');
          $table->dropColumn('precio_entradas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('festival_costes_entradas');

        Schema::table('festival_costes', function (Blueprint $table) {
          $table->integer('num_entradas')->after('aportacion');
          $table->float('precio_entradas')->after('num_entradas');
        });
    }
}
