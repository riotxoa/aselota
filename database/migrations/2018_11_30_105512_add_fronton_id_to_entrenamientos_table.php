<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFrontonIdToEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
          $table->integer('fronton_id')->after('contenido')->unsigned()->nullable()->default(null);
          $table->foreign('fronton_id')->references('id')->on('entr_frontones')->onUpdate('cascade')->onDelete('set null');
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
            $table->dropForeign('entrenamientos_fronton_id_foreign');
            $table->dropColumn('fronton_id');
        });
    }
}
