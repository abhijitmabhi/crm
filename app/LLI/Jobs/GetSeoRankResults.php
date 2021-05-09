<?php


namespace LocalheroPortal\LLI\Jobs;


use GoogleSearch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\Models\LLI\RankResult;

/**
 * Class GetSeoRankResults
 * @package LocalheroPortal\LLI\Jobs
 * @deprecated needs changes to RankResults to differentiate from local results.
 */
class GetSeoRankResults extends BaseJob
{
    protected $links;
    protected $keyword = "";
    protected $queryId;

    public function __construct(array $links, string $keyword, int $queryId)
    {
        $this->links = collect($links)->map(function ($value, $key) {
            return $this->parseLink($value);
        });
        $this->keyword = $keyword;
        $this->queryId = $queryId;
    }

    public function handle()
    {
        if (!config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $client = new GoogleSearch(env('SERP_API_KEY'));
        $query = [
            'engine' => 'google',
            'q' => $this->keyword,
            'google_domain' => 'google.de',
            'num' => 100,
            'hl' => 'de',
            'gl' => 'de',
            'start' => 0
        ];

        $json_results = $client->get_json($query);
        $results = collect($json_results->organic_results);

        $filtered = $results->map(function ($value, $key) {
            return [
                'title' => $value->title,
                'position' => $value->position,
                'link' => $this->parseLink($value->link)
            ];
        });

        RankResult::firstOrCreate([
            'fetched_at' => Carbon::now(),
            'results' => $filtered->toJson(),
            'seo_rank_query_id' => $this->queryId
        ]);
    }

    private function parseLink($link)
    {
        if (!Str::startsWith($link, ['http://', 'https://'])) {
            $link = "https://".$link;
        }
        return parse_url($link)['host'];
    }
}