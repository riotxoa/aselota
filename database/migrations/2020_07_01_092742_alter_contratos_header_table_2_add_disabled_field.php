<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratosHeaderTable2AddDisabledField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('contratos_header', function (Blueprint $table) {
          $table->boolean('disabled')->default(false)->after('fecha_fin');
          $table->text('observaciones')->nullable()->after('disabled');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('contratos_header', function (Blueprint $table) {
          $table->dropColumn('disabled');
          $table->dropColumn('observaciones');
      });
    }
}
