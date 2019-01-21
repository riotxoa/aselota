<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFestivalContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festival_contactos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('festival_id')->unsigned()->nullable()->default(null);
            $table->foreign('festival_id')->references('id')->on('festivales')->onUpdate('cascade')->onDelete('cascade');
            $table->string('contact_01_name', 100)->nullable()->default(null);
            $table->string('contact_01_desc', 100)->nullable()->default(null);
            $table->string('contact_01_telephone_1', 20)->nullable()->default(null);
            $table->string('contact_01_telephone_2', 20)->nullable()->default(null);
            $table->string('contact_01_email_1', 100)->nullable()->default(null);
            $table->string('contact_01_email_2', 100)->nullable()->default(null);
            $table->string('contact_02_name', 100)->nullable()->default(null);
            $table->string('contact_02_desc', 100)->nullable()->default(null);
            $table->string('contact_02_telephone_1', 20)->nullable()->default(null);
            $table->string('contact_02_telephone_2', 20)->nullable()->default(null);
            $table->string('contact_02_email_1', 100)->nullable()->default(null);
            $table->string('contact_02_email_2', 100)->nullable()->default(null);
            $table->text('observaciones')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('festival_contactos');
    }
}
