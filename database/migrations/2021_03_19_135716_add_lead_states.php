<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadStates extends Migration
{

    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->json('states')->default('[]');
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('states');
        });
    }
}
