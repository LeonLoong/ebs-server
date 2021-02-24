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
            $table->unsignedTinyInteger('car_manufacturer_id');
            $table->string('model', 100);
        });

        Schema::create('car_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer', 25);
            $table->string('logo', 150);
        });

        Schema::create('car_batteries', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('car_id');
            $table->unsignedTinyInteger('battery_id');
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
