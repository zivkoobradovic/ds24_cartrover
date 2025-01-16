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
            $table->string('product_name');
            $table->integer('quantity');
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
