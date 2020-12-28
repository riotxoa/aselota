<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMed2InformesPAccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med2_informes_p_acc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('desc')->nullable();
            $table->text('path')->nullable();
            $table->unsignedInteger('parte_id');
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
        Schema::dropIfExists('med2_informes_p_acc');
    }
}
