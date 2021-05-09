<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeightWidthAndRatioToLocationMediaItems extends Migration
{
    public function up()
    {
        Schema::table('location_media_items', function (Blueprint $table) {
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('ratio')->nullable();
        });
    }

    public function down()
    {
        Schema::table('location_media_items', function (Blueprint $table) {
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('ratio');
        });
    }
}
