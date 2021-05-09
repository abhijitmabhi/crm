<?php


namespace LocalheroPortal\LLI\Console\Commands;


use Illuminate\Console\Command;
use LocalheroPortal\LLI\Jobs\GetSeoRankResults;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\RankQuery;

/**
 * Class GetSeoRankings
 * @package LocalheroPortal\LLI\Console\Commands
 * @deprecated is using deprecated GetSeoRankResults
 */
class GetSeoRankings extends Command
{

    protected $signature = 'seorankings:get';
    protected $description = 'Get data for all stored Keywords';

    public function handle()
    {
        foreach (RankQuery::cursor() as $query) {
            $links = $query->locations->map(function (Location $location, $key) {
                return $location->company->url;
            })->toArray();

            dispatch(new GetSeoRankResults($links, $query->keyword, $query->id))->onQueue('crawler');
        }
    }
}