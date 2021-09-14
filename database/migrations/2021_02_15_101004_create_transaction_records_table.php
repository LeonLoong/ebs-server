<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('user_id')->nullable();
            $table->unsignedTinyInteger('battery_id');
            $table->unsignedTinyInteger('car_id')->nullable();
            $table->unsignedTinyInteger('payment_method_id')->nullable();
            $table->string('plate_no', 10);
            $table->string('phone_no', 15)->unique();
            $table->longText('service_point');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_records');
    }
}
