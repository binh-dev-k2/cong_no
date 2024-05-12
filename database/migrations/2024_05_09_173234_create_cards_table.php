<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('bank_code');
            $table->string('card_number');
            $table->string('account_number');
            $table->string('login_info');
            $table->date('date_due')->nullable();
            $table->date('date_return')->nullable();
            $table->string('account_name');
            $table->string('note')->nullable();
            $table->smallInteger('fee_percent');
            $table->bigInteger('total_money');
            $table->enum('formality', ['D', 'R']);
//            $table->bigInteger('fee');
            $table->bigInteger('pay_extra');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('cards');
    }
}
