<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class TransferLocationCategoriesToPivotTable extends Migration
{

    public function up()
    {
        Artisan::call('db:seed', [
            '--class' => 'TransferLocationCategoriesToPivot',
            '--force' => true
        ]);

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::drop('location_categories');
    }
}
