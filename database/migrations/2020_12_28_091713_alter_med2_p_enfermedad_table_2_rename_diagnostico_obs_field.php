<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMed2PEnfermedadTable2RenameDiagnosticoObsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('med2_p_enfermedad', function (Blueprint $table) {
          $table->renameColumn('diagnostico_obs', 'diagnostico_ini');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('med2_p_enfermedad', function (Blueprint $table) {
          $table->renameColumn('diagnostico_ini', 'diagnostico_obs');
      });
    }
}
