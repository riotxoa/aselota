<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrensaEventoPelotarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prensa_evento_pelotaris', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('prensa_evento_id')->unsigned();
            $table->foreign('prensa_evento_id')->references('id')->on('prensa_eventos')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('pelotari_id')->unsigned();
            $table->foreign('pelotari_id')->references('id')->on('pelotaris')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('asiste')->nullable()->default(null);
            $table->text('motivo')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prensa_evento_pelotaris');
    }
}
