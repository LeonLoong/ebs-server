<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('manufacturer_id');
            $table->string('model', 15);
            $table->string('description', 100)->nullable();
            $table->string('image', 50)->nullable();
            $table->mediumInteger('image_size')->nullable();
            $table->unique(['manufacturer_id', 'model', 'description']);
        });

        Schema::create('car_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer', 15)->unique();
            $table->string('image', 50)->nullable();
            $table->mediumInteger('image_size')->nullable();
        });

        Schema::create('car_batteries', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('car_id');
            $table->unsignedTinyInteger('battery_id');
            $table->boolean('upgrade')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
        Schema::dropIfExists('car_manufacturers');
        Schema::dropIfExists('car_has_batteries');
    }
}
