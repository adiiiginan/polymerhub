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
        Schema::table('article', function (Blueprint $table) {
            $table->string('heading')->after('title');
            $table->string('judul')->after('heading');
            $table->longText('indocontent')->after('content');
            $table->string('gambar')->nullable()->after('indocontent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn(['heading', 'judul', 'indocontent', 'gambar']);
        });
    }
};
