<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExcludedCategoriesToExpertSettings extends Migration
{

    public function up()
    {
        Schema::table('expert_settings', function (Blueprint $table) {
            $table->json('excluded_categories')->default('[]');
        });
    }

    public function down()
    {
        Schema::table('expert_settings', function (Blueprint $table) {
            $table->dropColumn('excluded_categories');
        });
    }
}
