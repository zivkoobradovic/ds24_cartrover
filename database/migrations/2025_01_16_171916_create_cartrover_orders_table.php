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
            $table->foreignId('digistore24_order_id')->constrained()->onDelete('cascade'); // Relacija sa ds24 orderom od koga ovaj order nastaje
            $table->foreignId('cartrover_integration_id')->constrained()->onDelete('cascade'); // Relacija sa cartrover integracijom koja pravi ovaj order
            $table->text('sent_order_request'); // JSON order koji je poslat na cartrover
            $table->integer('create_order_attempt_number'); // Broj pokusaja kreiranja ordera
            $table->boolean('order_sent_to_cartrover'); // U zavisnosti od statusa is respnse pri slanju na cartrover - success_code: true / success_code: false
            $table->text('received_order_response'); // Response cartrovera posle slanja sa aplikacije
            $table->boolean('received_from_cartrover'); // Da li je response primljen sa cartrovera true/false
            $table->string('cartrover_order_status'); // Status ordera na cartrover, ceo response. Generise se prilikom provere - da li je order shipped. Radi cron
            $table->boolean('is_shipped'); // true ili false. Ako je order shipped = true, ako nije shipped = false
            $table->string('carrier'); // Carrier iz response ako je cartrover order staus shipped
            $table->bigInteger('tracking_no'); // Tracking number sa cartrovera ako je order shipped
            $table->dateTime('ship_date'); // Datum slanja ordera sa Cartrover
            $table->text('shipped_items'); // // Items koji su poslati -  iz response prilikom provere order status
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
