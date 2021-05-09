<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleBusinessCategoryLocationTable extends Migration
{

    public function up()
    {
        Schema::create('google_business_category_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('google_business_category_id');
            $table->foreign('google_business_category_id', 'google_business_category_foreign')->references('id')->on('google_business_categories');
            $table->foreignId('location_id')->constrained();
            $table->string('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_business_category_location');
    }
}
