<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->enum('formality', ['R', 'D']);
            $table->unsignedBigInteger('customer_id');
            $table->string('card_number');
            $table->smallInteger('fee_percent');
            $table->bigInteger('total_money');
            $table->bigInteger('fee');
            $table->bigInteger('pay_extra');
            $table->string('status');
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
        Schema::dropIfExists('debts');
    }
}
