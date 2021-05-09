<?php

namespace LocalheroPortal\LLI\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Util\UrlUtil;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\RankQuery;

class LocationCompetitionStatisticsController extends Controller
{

    const MAX_QUERY_RESULTS = 50;
    const SHOWN_RESULTS = 5;

    public function __invoke(Request $request, Company $company, Location $location)
    {
        $request->validate([
            '$pastDays' => 'integer',
        ]);
        $pastDays = intval($request->pastDays);

        return $this->fetchKeywords($company, $location, $pastDays);
    }

    protected function fetchKeywords(Company $company, Location $location, int $pastDays)
    {
        $locationUrl = UrlUtil::getUrlDomain($location->website ?? $company->url);
        return $location->rankQueries
            ->map(function (RankQuery $query) use ($locationUrl, $pastDays) {
                $data = [
                    'keywordId' => $query->id,
                    'ranking' => [],
                    'position' => self::MAX_QUERY_RESULTS + 1
                ];

                $results = $query->rankResults()
                    ->orderBy('fetched_at', 'desc')
                    ->take($pastDays + 1)
                    ->get();

                $latestResult = $results->first();
                $referenceResult = $results->last();

                if ($latestResult == null) {
                    return $data;
                }

                $results = collect($latestResult->results);
                $position = $this->getRankingPosition($results, 'link', $locationUrl);
                $data['position'] = $position;
                $data['ranking'] = collect($this->getRanking($results, $position))->map(fn($rank) => collect($rank));

                $results = collect($referenceResult->results);
                for ($i = 0; $i < self::SHOWN_RESULTS; $i++) {
                    $rankResult = $data['ranking'][$i];
                    $referencePosition = $this->getRankingPosition($results, 'place_id', $rankResult['place_id']);
                    $rankResult['positionChange'] = $referencePosition - $rankResult['position'];
                }

                return $data;
            });
    }

    protected function getRankingPosition(Collection $ranking, string $key, string $value)
    {
        $rankingResult = $ranking->first(fn($rank) => $rank[$key] === $value);
        return $rankingResult != null ? $rankingResult['position'] : self::MAX_QUERY_RESULTS + 1;
    }

    protected function getRanking(Collection $results, int $position)
    {
        $count = $results->count();
        if ($position < 3) {
            return $results->splice(0, self::SHOWN_RESULTS);
        } elseif ($count - $position < 3 || $position > $count) {
            return $results->splice($count - self::SHOWN_RESULTS, self::SHOWN_RESULTS);
        } else {
            return $results->splice($position - 3, self::SHOWN_RESULTS);
        }
    }
}