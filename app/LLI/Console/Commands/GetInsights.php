<?php

namespace LocalheroPortal\LLI\Console\Commands;

use Illuminate\Console\Command;
use LocalheroPortal\LLI\Jobs\GetMetrics;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;

class GetInsights extends Command
{

    protected $signature = 'insights:get';
    protected $description = 'Gets Insights for Location';
    protected int $maxLocationsPerRequest = 10;

    public function handle()
    {
        // TODO_Release
        // TODO: revert change before release
        dispatch(new GetMetrics(Company::whereId(26)->first(), Location::whereId(1015)->first()))->onQueue('crawler');
//        $companies = Company::whereId(26)->get();
//        foreach ($companies as $company) {
//            foreach ($company->locations as $location) {
//                dispatch(new GetMetrics($company, $location))->onQueue('crawler');
//            }
//        }
        return true;
    }
}
