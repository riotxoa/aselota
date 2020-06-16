<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPelotarisTable2AddNumTrabajadorField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelotaris', function (Blueprint $table) {
            $table->char('num_trabajador', 6)->nullable()->after('DNI');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelotaris', function (Blueprint $table) {
            $table->dropColumn('num_trabajador');
        });
    }
}
