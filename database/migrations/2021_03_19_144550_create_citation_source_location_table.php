<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitationSourceLocationTable extends Migration
{
    public function up()
    {
        Schema::create('citation_source_location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('citation_source_id')->constrained();
            $table->string('state')->default('TODO');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citation_source_location');
    }
}
