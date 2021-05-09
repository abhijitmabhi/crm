<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeDefaultValueOfLocationStates extends Migration
{

    public function up()
    {
//        Schema::table('locations', function (Blueprint $table) {
//            $table->string('states')->default('[]')->change();
//        });
        // Das obere Statement geht nicht weil es irgendwie Probleme mit json als type gibt, geht leider nur raw
        DB::statement(' ALTER TABLE locations ALTER states SET DEFAULT "[]" ');

    }

    public function down()
    {
        /* Schema::table('locations', function (Blueprint $table) {
             $table->json('states')
                   ->default('["CITATIONS_DONE","ACCESS_DATA_SENT","ACTIVATED","INFORMATION_AND_PICTURES_EXIST"]')->change();
         });*/
        DB::statement(' ALTER TABLE locations ALTER states SET DEFAULT "["CITATIONS_DONE","ACCESS_DATA_SENT","ACTIVATED","INFORMATION_AND_PICTURES_EXIST"]" ');

    }
}
