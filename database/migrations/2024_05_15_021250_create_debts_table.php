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
            $table->string('name');
            $table->string('phone');
            $table->string('card_number');
            $table->enum('formality', ['R', 'D']);
            $table->bigInteger('fee');
            $table->bigInteger('pay_extra')->nullable();
            $table->bigInteger('total_amount')->nullable();
            $table->tinyInteger('status')->default(Debt::STATUS_UNPAID);
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
