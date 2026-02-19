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
        Schema::create('lion_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kota_id');
            $table->string('nama');
            $table->timestamps();

            $table->foreign('kota_id')->references('id')->on('lion_kota')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lion_kecamatan');
    }
};
