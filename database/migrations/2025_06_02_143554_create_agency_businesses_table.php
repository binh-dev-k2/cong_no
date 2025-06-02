<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies');
            $table->foreignId('machine_id')->constrained('machines');
            $table->unsignedBigInteger('total_money');
            $table->decimal('profit', 15, 2)->nullable();
            $table->string('image_front')->nullable();
            $table->string('image_summary')->nullable();
            $table->string('standard_code')->nullable();
            $table->decimal('amount_to_pay', 15, 2)->nullable();
            $table->boolean('is_completed')->default(false);
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
        Schema::dropIfExists('agency_businesses');
    }
}
