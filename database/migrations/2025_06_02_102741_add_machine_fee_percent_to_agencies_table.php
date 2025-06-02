<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMachineFeePercentToAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->decimal('machine_fee_percent', 10, 2)->after('fee_percent')->default(0);
            $table->unsignedBigInteger('owner_id')->after('machine_fee_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('machine_fee_percent');
            $table->dropColumn('owner_id');
        });
    }
}
