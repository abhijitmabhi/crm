<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorSeoRankModels extends Migration
{

    public function up()
    {
        Schema::table('location_seo_rank_query', function (Blueprint $table) {
            $table->dropForeign('location_seo_rank_query_location_id_foreign');
            $table->dropForeign('location_seo_rank_query_seo_rank_query_id_foreign');
        });
        Schema::drop('location_seo_rank_query');

        Schema::table('seo_rank_results', function (Blueprint $table) {
            $table->dropForeign(['seo_rank_query_id']);
            $table->renameColumn('seo_rank_query_id', 'rank_query_id');
        });
        Schema::rename('seo_rank_queries', 'rank_queries');
        Schema::rename('seo_rank_results', 'rank_results');
        Schema::table('rank_results', function (Blueprint $table) {
            $table->foreign('rank_query_id')->references('id')->on('rank_queries');
        });

        Schema::table('rank_queries', function (Blueprint $table) {
            $table->foreignId('location_id')->constrained();
        });
    }

    public function down()
    {
        Schema::table('rank_queries', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });

        Schema::table('rank_results', function (Blueprint $table) {
            $table->dropForeign(['rank_query_id']);
        });
        Schema::rename('rank_queries', 'seo_rank_queries');
        Schema::rename('rank_results', 'seo_rank_results');
        Schema::table('seo_rank_results', function (Blueprint $table) {
            $table->renameColumn('rank_query_id', 'seo_rank_query_id');
            $table->foreign('seo_rank_query_id')->references('id')->on('seo_rank_queries');
        });

        //create location_seo_rank_query
    }
}
