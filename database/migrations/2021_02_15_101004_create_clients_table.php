<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('battery_id');
            $table->unsignedTinyInteger('car_id')->nullable();
            $table->unsignedTinyInteger('payment_method_id')->nullable();
            $table->string('handphone_number')->unique();
            $table->tinyInteger('total_visits')->default(1);
            $table->tinyInteger('total_transactions')->default(1);
            $table->tinyInteger('service_difficulty')->default(1);
            $table->longText('service_points');
            $table->decimal('discount', 5, 0);
            $table->longText('description')->nullable();
            $table->timestamp('first_visit_at');
            $table->timestamp('last_visit_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
