<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateGoogleBusinessCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('google_business_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gcid');
            $table->timestamps();
            $table->softDeletes();
        });

        Artisan::call('db:seed', [
            '--class' => 'TransferOldLocationCategories',
            '--force' => true
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('google_business_categories');
    }
}
