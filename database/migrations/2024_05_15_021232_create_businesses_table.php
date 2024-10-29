<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->float('fee_percent');
            $table->string('card_number')->index();
            $table->bigInteger('total_money');
            $table->enum('formality', ['R', 'Đ']);
            $table->bigInteger('fee');
            $table->bigInteger('pay_extra')->nullable();
            $table->string('bank_code')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
