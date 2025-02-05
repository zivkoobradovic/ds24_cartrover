<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('digistore24_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cartrover_integration_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('buyer_email');

            $table->string('buyer_first_name');
            $table->string('buyer_last_name');
            $table->string('buyer_address_street');
            $table->string('buyer_address_street2')->nullable();
            $table->string('buyer_address_phone_no')->nullable();
            $table->string('buyer_address_company')->nullable();
            $table->string('buyer_address_city');
            $table->string('buyer_address_zipcode');
            $table->string('buyer_address_state');
            $table->string('buyer_address_country');
            $table->string('address_first_name');
            $table->string('address_last_name');

            $table->text('product_details');
            $table->date('order_date');
            $table->time('order_time');
            $table->string('pay_method');
            $table->text('json_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digistore24_orders');
    }
};
