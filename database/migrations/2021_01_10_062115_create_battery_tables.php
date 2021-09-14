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
            $table->unsignedTinyInteger('manufacturer_id');
            $table->unsignedTinyInteger('model_id');
            $table->unsignedTinyInteger('series_id')->nullable();
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('trade_in_id');
            $table->decimal('price', 5, 0);
            $table->tinyInteger('warranty');
            $table->tinyInteger('stock')->default(1);
            $table->smallInteger('volt')->default(12);
            $table->smallInteger('ah');
            $table->smallInteger('cca');
            $table->smallInteger('rc');
            $table->smallInteger('length');
            $table->smallInteger('width');
            $table->smallInteger('height');
            $table->string('image', 50)->nullable();
            $table->mediumInteger('image_size')->nullable();
            $table->longText('description')->nullable();
            $table->json('specifications')->nullable(); 
            $table->timestamps();
            $table->unique(['manufacturer_id', 'model_id']);
        });

        Schema::create('battery_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer', 15)->unique();
            $table->string('image', 50);
            $table->mediumInteger('image_size')->nullable();
            $table->boolean('available')->default(1);
            $table->string('description_bm', 1000);
            $table->string('description_en', 1000);
        });

        Schema::create('battery_models', function (Blueprint $table) {
            $table->id();
            $table->string('model', 15)->unique();
            $table->string('model_reference', 15)->nullable();
        });
        
        Schema::create('battery_specifications', function (Blueprint $table) {
            $table->id();
            $table->string('spec', 30)->unique();
            $table->longText('description')->nullable();
        });

        Schema::create('battery_trade_ins', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 5, 0)->unique();
        });

        Schema::create('battery_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('manufacturer_id');
            $table->string('series', 30)->unique();
            $table->longText('description_bm');
            $table->longText('description_en');
        });

        Schema::create('battery_types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10)->unique();
            $table->json('pros_bm'); 
            $table->json('pros_en');   
            $table->json('cons_bm');   
            $table->json('cons_en');   
            $table->longText('description_bm');
            $table->longText('description_en');
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
        Schema::dropIfExists('battery_models');
        Schema::dropIfExists('battery_specifications');
        Schema::dropIfExists('battery_trade_ins');
        Schema::dropIfExists('battery_series');
        Schema::dropIfExists('battery_types');
    }
}
