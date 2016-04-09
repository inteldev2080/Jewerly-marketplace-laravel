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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('order_id');
            $table->integer('total')->default(0);
            $table->integer('shipping_option_id')->default(0);
            $table->integer('tax_option_id')->default(0);
            $table->integer('grand_total')->default(0);
            $table->string('shipping_address1');
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zipcode');
            $table->string('shipping_country');
            $table->string('shipping_phonenumber')->nullable();
            $table->string('billing_address1');
            $table->string('billing_address2')->nullable();
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_zipcode');
            $table->string('billing_country');
            $table->string('billing_phonenumber')->nullable();
            $table->string('payment_intent')->default('');
            $table->smallInteger('status_payment')->default(1); // 1: unpaid, 2: paid
            $table->string('status_payment_reason')->default('');
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
        Schema::dropIfExists('orders');
    }
};
