<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumsTypeToAgencyBusinessessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_businessess', function (Blueprint $table) {
            $table->float('profit')->nullable()->after('total_money');
            $table->float('machine_fee_percent')->nullable()->after('profit');
            $table->string('image_front')->nullable()->change();
            $table->string('image_summary')->nullable()->change();
            $table->string('standard_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agency_businessess', function (Blueprint $table) {
            $table->string('image_front')->change();
            $table->string('image_summary')->change();
            $table->string('standard_code')->change();
            $table->dropColumn('profit');
            $table->dropColumn('machine_fee_percent');
        });
    }
}
