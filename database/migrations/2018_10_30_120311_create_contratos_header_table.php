<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos_header', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 100);
            $table->integer('pelotari_id')->unsigned()->nullable()->default(null);
            $table->foreign('pelotari_id')->references('id')->on('pelotaris')->onUpdate('cascade')->onDelete('set null');
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos_header');
    }
}
