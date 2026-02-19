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
        Schema::create('fedex_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idtrans');
            $table->string('shipment_id');
            $table->string('tracking_number');
            $table->text('label_url');
            $table->string('service_type');
            $table->decimal('total_charge', 8, 2);
            $table->string('currency');
            $table->text('rate_response');
            $table->text('shipper_address');
            $table->text('recipient_address');
            $table->decimal('weight', 8, 2);
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
        Schema::dropIfExists('fedex_shipment');
    }
};
