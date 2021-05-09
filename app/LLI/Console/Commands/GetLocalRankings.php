<?php


namespace LocalheroPortal\LLI\Console\Commands;


use Illuminate\Console\Command;
use LocalheroPortal\LLI\Jobs\GetLocalRankResults;
use LocalheroPortal\Models\LLI\RankQuery;

class GetLocalRankings extends Command
{

    protected $signature = 'localrankings:get';
    protected $description = 'Get data for all stored Keywords';

    public function handle()
    {
        foreach (RankQuery::cursor() as $query) {
            $websiteUrl = $query->location->website ?? $query->location->company->url;
            dispatch(new GetLocalRankResults($websiteUrl, $query->keyword, $query->id))->onQueue('crawler');
        }
    }
}