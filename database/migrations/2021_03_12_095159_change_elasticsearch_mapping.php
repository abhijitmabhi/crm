<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class ChangeElasticsearchMapping extends Migration
{

    public function up()
    {
        Artisan::call('elastic:migrate-model', [
            'model' => "LocalheroPortal\Models\Lead",
            'target-index' => "lead_index_202103121040"
        ]);
        Artisan::call('elastic:migrate-model', [
            'model' => "LocalheroPortal\Models\LLI\Company",
            'target-index' => "company_index_202103121040"
        ]);
    }
}
