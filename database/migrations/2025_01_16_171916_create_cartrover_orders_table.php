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
        Schema::create('cartrover_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digistore24_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('cartrover_integration_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartrover_orders');
    }
};
