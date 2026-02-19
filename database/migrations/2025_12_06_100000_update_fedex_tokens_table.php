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
        Schema::table('fedex_tokens', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('access_token');
            $table->dropColumn('expires_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fedex_tokens', function (Blueprint $table) {
            $table->integer('expires_in')->nullable();
            $table->dropColumn('expires_at');
        });
    }
};
