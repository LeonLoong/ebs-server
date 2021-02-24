<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatteryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batteries', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('battery_manufacturer_id');
            $table->unsignedTinyInteger('battery_series_id');
            $table->unsignedTinyInteger('battery_type_id');
            $table->unsignedTinyInteger('battery_trade_in_id');
            $table->string('model', 25);
            $table->string('model_reference', 25);
            $table->decimal('price', 5, 0);
            $table->tinyInteger('warranty');
            $table->tinyInteger('stock')->default(1);
            $table->string('image', 150);
            $table->longText('description');
            $table->json('specifications'); 
            $table->timestamps();
            $table->unique(['battery_manufacturer_id', 'model']);
        });

        Schema::create('battery_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer', 25);
            $table->string('logo', 150);
            $table->boolean('available')->default(1);
            $table->longText('description_bm');
            $table->longText('description_en');
            $table->longText('description_zh');
        });

        Schema::create('battery_specifications', function (Blueprint $table) {
            $table->id();
            $table->string('spec', 50);
            $table->longText('description')->nullable();
        });

        Schema::create('battery_trade_ins', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 5, 0);
        });

        Schema::create('battery_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('battery_manufacturer_id');
            $table->string('series', 25);
            $table->longText('description_bm');
            $table->longText('description_en');
            $table->longText('description_zh');
        });

        Schema::create('battery_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_bm', 50);
            $table->string('type_en', 50);
            $table->string('type_zh', 50);
            $table->json('pros_bm'); 
            $table->json('pros_en');   
            $table->json('pros_zh');   
            $table->json('cons_bm');   
            $table->json('cons_en');   
            $table->json('cons_zh');
            $table->longText('description_bm');
            $table->longText('description_en');
            $table->longText('description_zh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batteries');
        Schema::dropIfExists('battery_manufacturers');
        Schema::dropIfExists('battery_specifications');
        Schema::dropIfExists('battery_trade_ins');
        Schema::dropIfExists('battery_series');
        Schema::dropIfExists('battery_types');
    }
}
