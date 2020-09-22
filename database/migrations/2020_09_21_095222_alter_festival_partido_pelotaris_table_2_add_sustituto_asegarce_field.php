<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFestivalPartidoPelotarisTable2AddSustitutoAsegarceField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('festival_partido_pelotaris', function (Blueprint $table) {
          $table->boolean('sustituto_asegarce')->nullable()->default(true)->after('sustituto_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('festival_partido_pelotaris', function (Blueprint $table) {
          $table->dropColumn('sustituto_asegarce');
      });
    }
}
