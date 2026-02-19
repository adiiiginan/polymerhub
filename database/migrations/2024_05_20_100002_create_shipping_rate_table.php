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
        Schema::create('shipping_rate', function (Blueprint $table) {
            $table->id();
            $table->integer('idexpedisi');
            $table->string('carrier');
            $table->string('service_type');
            $table->string('origin');
            $table->string('destination');
            $table->decimal('weight', 8, 2);
            $table->decimal('price', 8, 2);
            $table->string('currency');
            $table->string('etd');
            $table->text('response_json');
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
        Schema::dropIfExists('shipping_rate');
    }
};
