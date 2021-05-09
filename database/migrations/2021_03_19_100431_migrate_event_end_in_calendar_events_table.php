<?php

use Database\Seeders\MigrateEventEndSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateEventEndInCalendarEventsTable extends Migration
{

    public function up()
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            Artisan::call('db:seed', [
                '--class' => MigrateEventEndSeeder::class
            ]);
        });
    }

    public function down()
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            //
        });
    }
}
