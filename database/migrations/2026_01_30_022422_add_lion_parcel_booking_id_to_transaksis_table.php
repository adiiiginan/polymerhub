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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('lion_parcel_booking_id')->nullable()->after('resi');
            $table->string('lion_parcel_stt')->nullable()->after('lion_parcel_booking_id');
            $table->text('lion_parcel_response')->nullable()->after('lion_parcel_stt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['lion_parcel_booking_id', 'lion_parcel_stt', 'lion_parcel_response']);
        });
    }
};
