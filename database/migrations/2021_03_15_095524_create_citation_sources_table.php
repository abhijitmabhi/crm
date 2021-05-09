<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitationSourcesTable extends Migration
{
    public function up()
    {
        Schema::create('citation_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('url');
            $table->integer('score');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citation_sources');
    }
}
