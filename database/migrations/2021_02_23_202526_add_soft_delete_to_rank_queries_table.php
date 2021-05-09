<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToRankQueriesTable extends Migration
{

    public function up()
    {
        Schema::table('rank_queries', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('rank_queries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
