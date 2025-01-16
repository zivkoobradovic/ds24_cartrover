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
        Schema::create('cartrover_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('vendor_id')->constrained();
            $table->string('ipn_pass')->unique();
            $table->string('http_header')->unique();
            $table->string('auth')->unique();
            $table->string('ds24_api_key')->unique();
            $table->string('cr_api_user')->unique();
            $table->string('cr_api_pass')->unique();
            $table->string('ipn_url')->unique();
            $table->json('products');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartrover_integrations');
    }
};
