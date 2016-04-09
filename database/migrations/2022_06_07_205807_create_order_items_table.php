<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->integer('product_id')->nullable();
            $table->string('product_name');
            $table->string('product_variant');
            $table->string('product_variant_name');
            $table->tinyInteger('quantity')->unsigned();
            $table->smallInteger('price')->unsigned();
            $table->smallInteger('status_fulfillment')->default(1); // 1: pending, 2: shipped, 3: delivered
            $table->string('status_tracking')->nullable();
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
        Schema::dropIfExists('order_items');
    }
};
