<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixColumsTypeToMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->float('visa_fee_percent')->nullable()->change();
            $table->float('master_fee_percent')->nullable()->change();
            $table->float('jcb_fee_percent')->nullable()->change();
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
            $table->float('visa_fee_percent')->nullable(false)->change();
            $table->float('master_fee_percent')->nullable(false)->change();
            $table->float('jcb_fee_percent')->nullable(false)->change();
        });
    }
}
