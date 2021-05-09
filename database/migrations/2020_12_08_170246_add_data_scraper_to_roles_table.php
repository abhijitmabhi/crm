<?php

use Database\Seeders\DataScraperRoleSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class AddDataScraperToRolesTable extends Migration
{

    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            Artisan::call('db:seed', [
                '--class' => DataScraperRoleSeeder::class
            ]);
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {

        });
    }
}
