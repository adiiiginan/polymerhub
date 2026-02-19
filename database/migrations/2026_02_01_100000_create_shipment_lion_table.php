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
        Schema::create('shipment_lion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idtrans')->nullable();
            $table->string('tracking_number', 50)->nullable();
            $table->string('booking_id', 50)->nullable();
            $table->string('service_type', 30)->nullable();
            $table->decimal('total_charge', 12, 2)->nullable();
            $table->string('status', 30)->nullable();
            $table->longText('rate_response')->nullable();
            $table->text('shipper_address')->nullable();
            $table->text('recipient_address')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('currency', 10)->nullable();
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
        Schema::dropIfExists('shipment_lion');
    }
};
