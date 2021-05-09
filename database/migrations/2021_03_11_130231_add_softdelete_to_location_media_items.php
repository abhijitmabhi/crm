<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToLocationMediaItems extends Migration
{
    public function up()
    {
        Schema::table('location_media_items', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('location_media_items', function (Blueprint $table) {
            $table->dropSoftDeletes();

        });
    }
}
