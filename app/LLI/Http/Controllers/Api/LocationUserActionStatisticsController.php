<?php

namespace LocalheroPortal\LLI\Http\Controllers\Api;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationInsight;

class LocationUserActionStatisticsController extends Controller
{
    const DAY_FORMAT = "d.m";
    const WEEK_FORMAT = "K\W W";
    const MONTH_FORMAT = "m/Y";

    const EMPTY_INSIGHT_RESULTSET = [
        'ACTIONS_WEBSITE' => 0,
        'ACTIONS_PHONE' => 0,
        'ACTIONS_DRIVING_DIRECTIONS' => 0,
    ];

    public function __invoke(Request $request, Company $company, Location $location)
    {
        $request->validate([
            'pastDays' => 'integer',
        ]);
        $pastDays = intval($request->pastDays);
        $grouping = $this->getGrouping($pastDays);

        $queryConfigParameters = collect(['location' => $location, 'pastDays' => $pastDays, 'grouping' => $grouping, 'isDetailedView' => $request->isDetailedView, 'locationInsightType' => $request->locationInsightType]);
        $data = $this->fetchActions($queryConfigParameters);
        if($queryConfigParameters['isDetailedView'] == "true") {
            $data = $this->aggregateData($data);
        }
        return $data;
    }

    private function getGrouping(int $pastDays): string
    {
        switch ($pastDays) {
            case $pastDays <= 7:
                return 'days';
            case $pastDays <= 31:
                return 'weeks';
            default:
                return 'months';
        }
    }

    public function fetchActions(Collection $parameters)
    {
        $latestInsight = LocationInsight::whereLocationId($parameters['location']['id'])->latest('date')->first();
        $endDate = Carbon::now();
        if ($latestInsight) {
            $endDate = Carbon::parse($latestInsight->date);
        }
        $startDate = $endDate->copy()->subDays($parameters['pastDays'] - 1);

        $actions = null;
        switch ($parameters['grouping']) {
            case 'days':
                $actions = $this->fetchInsightsByDay($parameters, $startDate, $endDate);
                $period = CarbonPeriod::create($startDate, '1 days', $endDate);
                $actions = $this->processResults($actions, $period, self::DAY_FORMAT);
                break;
            case 'weeks':
                $startDate->startOfWeek()->addWeek();
                $actions = $this->fetchInsightsByWeek($parameters, $startDate, $endDate);
                $period = CarbonPeriod::create($startDate, '1 weeks', $endDate);
                $actions = $this->processResults($actions, $period, self::WEEK_FORMAT);
                break;
            default:
                $startDate->startOfMonth()->addMonth();
                $actions = $this->fetchInsightsByMonth($parameters, $startDate, $endDate);
                $period = CarbonPeriod::create($startDate, '1 months', $endDate);
                $actions = $this->processResults($actions, $period, self::MONTH_FORMAT);
        }
        return $actions;
    }

    public function fetchInsightsByDay(Collection $parameters, Carbon $startDate, Carbon $endDate)
    {
        return $this->getInsightsQuery($parameters, $startDate, $endDate)
            ->selectRaw('Date(date) as date, type, value')
            ->get();
    }

    public function getInsightsQuery(Collection $parameters, Carbon $startDate, Carbon $endDate)
    {
        return LocationInsight::query()
            ->where('location_id', $parameters['location']['id'])
            ->where('type', 'like', $parameters['locationInsightType'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date');
    }

    public function fetchInsightsByWeek(Collection $parameters, Carbon $startDate, Carbon $endDate)
    {
        return $this->getInsightsQuery($parameters, $startDate, $endDate)
            ->selectRaw('date, type, sum(value) as value')
            ->groupByRaw('YEARWEEK(date), type')
            ->get();
    }

    public function fetchInsightsByMonth(Collection $parameters, Carbon $startDate, Carbon $endDate)
    {
        return $this->getInsightsQuery($parameters, $startDate, $endDate)
            ->selectRaw('date, type, sum(value) as value')
            ->groupByRaw('MONTH(date), YEAR(date), type')
            ->get();
    }

    public function processResults(EloquentCollection $entries, CarbonPeriod $period, string $format)
    {
        $entries = $entries
            ->reduce(function (Collection $collection, object $obj) use ($format) {
                $date = CarbonImmutable::parse($obj->date)->format($format);
                if ($arr = $collection->get($date)) {
                    $arr = array_merge($arr, $this->reduceObj($obj));
                    $collection->put($date, $arr);
                } else {
                    $collection->put($date, $this->reduceObj($obj));
                }
                return $collection;
            }, new Collection());

        foreach ($period as $date) {
            $date = $date->format($format);
            if (!$entries->get($date)) {
                $entries->put($date, self::EMPTY_INSIGHT_RESULTSET);
            }
        }

        return $entries;
    }

    public function aggregateData(Collection $data) {
        $sum = 0;
        $dataArray = $data->toArray();
        foreach($dataArray as $firstKey => $entry) {
           foreach($entry as $secondKey => $value) {
                $sum += $value;
                $dataArray[$firstKey][$secondKey] = $sum;
           }
        }
        $dataArray = collect($dataArray);
        return $dataArray;
    }

    protected function reduceObj(object $object)
    {
        return [$object->type => $object->value];
    }


}