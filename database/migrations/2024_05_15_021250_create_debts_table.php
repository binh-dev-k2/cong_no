<?php

use App\Models\Debt;
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
            $table->string('account_name')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('card_number');
            $table->bigInteger('total_money')->nullable();
            $table->enum('formality', ['R', 'Đ']);
            $table->bigInteger('fee');
            $table->bigInteger('pay_extra')->nullable();
            $table->bigInteger('total_amount')->nullable();
            $table->tinyInteger('status')->default(Debt::STATUS_UNPAID);
            $table->unsignedBigInteger('business_id')->nullable();
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
