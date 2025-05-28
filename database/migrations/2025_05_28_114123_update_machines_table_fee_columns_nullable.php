<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateMachinesTableFeeColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sử dụng raw SQL để thay đổi cột thành nullable mà không cần Doctrine DBAL
        DB::statement('ALTER TABLE machines MODIFY visa_fee_percent FLOAT NULL');
        DB::statement('ALTER TABLE machines MODIFY master_fee_percent FLOAT NULL');
        DB::statement('ALTER TABLE machines MODIFY jcb_fee_percent FLOAT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Đặt lại thành NOT NULL
        DB::statement('ALTER TABLE machines MODIFY visa_fee_percent FLOAT NOT NULL');
        DB::statement('ALTER TABLE machines MODIFY master_fee_percent FLOAT NOT NULL');
        DB::statement('ALTER TABLE machines MODIFY jcb_fee_percent FLOAT NOT NULL');
    }
}
