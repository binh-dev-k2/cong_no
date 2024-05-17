<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_money', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->bigInteger('money');
            $table->boolean('is_money_checked')->default(0);
            $table->text('note')->nullable();
            $table->boolean('is_note_checked')->default(0);
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
        Schema::dropIfExists('business_money');
    }
}
