<?php

use Database\Seeders\AddCitationSourcesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCitationSources extends Migration
{
    public function up()
    {
        Schema::table('citation_sources', function (Blueprint $table) {
            Artisan::call('db:seed', [
                '--class' => AddCitationSourcesSeeder::class
            ]);
        });
    }
}
