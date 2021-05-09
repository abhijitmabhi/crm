<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateOpeningHoursToLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            Artisan::call('db:seed', [
                '--class' => MigrateOpeningHoursSeeder::class
            ]);
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            //
        });
    }
}
