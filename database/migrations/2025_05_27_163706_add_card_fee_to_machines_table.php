<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardFeeToMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->float('visa_fee_percent')->after('fee_percent')->default(0);
            $table->float('master_fee_percent')->after('visa_fee_percent')->default(0);
            $table->float('jcb_fee_percent')->after('master_fee_percent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn('visa_fee_percent');
            $table->dropColumn('master_fee_percent');
            $table->dropColumn('jcb_fee_percent');
        });
    }
}
