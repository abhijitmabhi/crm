<?php

use Database\Seeders\MigrateZipSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateZipDataInLeadsTable extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            Artisan::call('db:seed', [
                '--class' => MigrateZipSeeder::class
            ]);
        });
    }
}
