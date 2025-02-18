<?php

use App\Models\Card;
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
            $table->string('account_number')->nullable();
            $table->string('login_info')->nullable();
            $table->smallInteger('date_due')->nullable();
            $table->date('date_return')->nullable();
            $table->string('account_name');
            $table->float('fee_percent')->nullable();
            $table->string('note')->nullable();
            $table->tinyInteger('status')->default(Card::STATUS_SHOW);
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
