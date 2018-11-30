<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsToNullableInEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
          $table->boolean('asistencia')->nullable()->change();
          $table->string('duracion',3)->nullable()->change();
          $table->text('motivo')->nullable()->change();
          $table->text('pre_entreno')->nullable()->change();
          $table->text('contenido')->nullable()->change();
          $table->text('post_entreno')->nullable()->change();
          $table->text('comentarios')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
          $table->boolean('asistencia')->nullable(false)->change();
          $table->string('duracion',3)->nullable(false)->change();
          $table->text('motivo')->nullable(false)->change();
          $table->text('pre_entreno')->nullable(false)->change();
          $table->text('contenido')->nullable(false)->change();
          $table->text('post_entreno')->nullable(false)->change();
          $table->text('comentarios')->nullable(false)->change();
        });
    }
}
