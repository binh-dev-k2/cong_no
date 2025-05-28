<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateFeeColumnsToNotNullWithDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Đổi tên cột fee_percent thành napas_fee_percent
        DB::statement('ALTER TABLE machines CHANGE fee_percent napas_fee_percent FLOAT');

        Schema::table('machines', function (Blueprint $table) {
            $table->float('amex_fee_percent')->after('jcb_fee_percent')->default(0);
        });

        // Trước tiên, cập nhật tất cả các giá trị NULL thành 0
        DB::statement('UPDATE machines SET visa_fee_percent = 0 WHERE visa_fee_percent IS NULL');
        DB::statement('UPDATE machines SET master_fee_percent = 0 WHERE master_fee_percent IS NULL');
        DB::statement('UPDATE machines SET jcb_fee_percent = 0 WHERE jcb_fee_percent IS NULL');

        // Sau đó, thay đổi cột thành NOT NULL với default = 0
        DB::statement('ALTER TABLE machines MODIFY napas_fee_percent FLOAT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE machines MODIFY visa_fee_percent FLOAT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE machines MODIFY master_fee_percent FLOAT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE machines MODIFY jcb_fee_percent FLOAT NOT NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Đặt lại thành NULL
        DB::statement('ALTER TABLE machines MODIFY visa_fee_percent FLOAT NULL');
        DB::statement('ALTER TABLE machines MODIFY master_fee_percent FLOAT NULL');
        DB::statement('ALTER TABLE machines MODIFY jcb_fee_percent FLOAT NULL');

        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn('amex_fee_percent');
        });

        // Đổi tên cột napas_fee_percent trở lại thành fee_percent
        DB::statement('ALTER TABLE machines CHANGE napas_fee_percent fee_percent FLOAT');
    }
}
