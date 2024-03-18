<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card', function (Blueprint $table) {
            $table->id();
            $table->string('card_number');
            $table->string('account_number');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('note')->nullable();
            $table->dateTime('date_due')->nullable();
            $table->dateTime('date_return')->nullable();
            $table->string('card_name');
            $table->string('login_info');
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
        Schema::dropIfExists('card');
    }
}
