<?php


namespace LocalheroPortal\LLI\Jobs;


use GoogleSearch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Core\Util\UrlUtil;
use LocalheroPortal\LLI\Feature\Location\LocationValidationUseCase;
use LocalheroPortal\Models\LLI\RankQuery;
use LocalheroPortal\Models\LLI\RankResult;

class GetLocalRankResults extends BaseJob
{

    const MAX_QUERY_RESULTS = 50;

    protected $websiteUrl;
    protected $keyword = "";
    protected $queryId;

    public function __construct(string $websiteUrl, string $keyword, int $queryId)
    {
        $this->websiteUrl = UrlUtil::getUrlDomain($websiteUrl);
        $this->keyword = $keyword;
        $this->queryId = $queryId;
    }

    public function handle()
    {
        if (!config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $rankResult = RankResult::whereRankQueryId($this->queryId)
                ->whereDate('fetched_at', '=', Carbon::now()->toDateString())
                ->first();
        if ($rankResult != null) {
            return;
        }

        $rankQuery = RankQuery::whereId($this->queryId)->first();
        $location = $rankQuery->location;
        $results = $this->getLocalSearchResult($location);

        $filtered = $results->map(function ($value, $key) {
            return [
                'title' => $value->title,
                'position' => $value->position,
                'place_id' => $value->place_id,
                'type' => $value->type ?? '',
                'link' => UrlUtil::getUrlDomain($value->links->website ?? '')
            ];
        });

        RankResult::firstOrCreate([
            'fetched_at' => Carbon::now(),
            'results' => $filtered,
            'rank_query_id' => $this->queryId
        ]);

        $useCase = new LocationValidationUseCase($location);
        $useCase->onStatisticsChanged();
        $location->save();
    }

    private function getLocalSearchResult($location)
    {
        $client = new GoogleSearch(env('SERP_API_KEY'));
        //TODO: change for locations outside of Germany
        $query = [
            'tbm' => 'lcl',
            'location' => $location->city . ', Germany',
            'engine' => 'google',
            'q' => $this->keyword,
            'google_domain' => 'google.de',
            'num' => self::MAX_QUERY_RESULTS,
            'hl' => 'de',
            'gl' => 'de',
        ];

        $json_results = $client->get_json($query);
        return collect($json_results->local_results);
    }
}