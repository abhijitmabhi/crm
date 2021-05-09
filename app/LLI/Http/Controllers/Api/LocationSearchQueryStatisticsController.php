<?php

namespace LocalheroPortal\LLI\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationInsight;

class LocationSearchQueryStatisticsController extends Controller
{

    public function __invoke(Request $request, Company $company, Location $location)
    {
        $request->validate([
            '$pastDays' => 'integer',
        ]);
        $pastDays = intval($request->pastDays);

        $data = $this->fetchInsights($location, $pastDays);

        return $data;
    }

    protected function fetchInsights(Location $location, int $pastDays)
    {
        $latestInsight = LocationInsight::whereLocationId($location->id)->latest('date')->first();
        $endDate = Carbon::now();
        if ($latestInsight) {
            $endDate = Carbon::parse($latestInsight->date);
        }
        $startDate = $endDate->copy()->subDays($pastDays - 1);

        return LocationInsight::query()
            ->selectRaw("type, SUM(value) AS total")
            ->groupBy('type')
            ->where('location_id', $location->id)
            ->where('type', 'like', 'QUERIES_%')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->mapWithKeys(function ($entry) {
                return [$entry->type => (int) $entry->total];
            });
    }
}