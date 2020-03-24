<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMedLesionesTable2AddMedicoTxtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('med_lesiones', function (Blueprint $table) {
            $table->text('med_medico_txt')->after('med_medico_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_lesiones', function (Blueprint $table) {
            $table->dropColumn('med_medico_txt');
        });
    }
}
