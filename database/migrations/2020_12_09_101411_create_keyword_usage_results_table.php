<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordUsageResultsTable extends Migration
{

    public function up()
    {
        Schema::create('keyword_usage_results', function (Blueprint $table) {
            $table->id();
            $table->foreignID('location_id')->constrained();
            $table->dateTime('fetched_at')->useCurrent();
            $table->json('results');
        });
    }

    public function down()
    {
        Schema::dropIfExists('keyword_usage_results');
    }
}
