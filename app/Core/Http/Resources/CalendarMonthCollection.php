<?php

namespace LocalheroPortal\Core\Http\Resources;

use Carbon\CarbonImmutable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class CalendarMonthCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection
            ->mapToGroups(function ($expert) {
                $day = CarbonImmutable::parse($expert->date)->get('day');
                return [$expert->user_id => [
                    'id' => $expert->user_id,
                    'name' => $expert->last_name . ', ' .  $expert->first_name  ,
                    'day' => $day,
                    'events' => $expert->events,
                ]];
            })
            ->map(function ($experts) {
                $data = [
                    'id' => $experts[0]['id'],
                    'name' => $experts[0]['name'],
                    'dates' => new Collection(),
                ];
                foreach ($experts as ['day' => $day, 'events' => $events]) {
                    $data['dates']->put($day, $events);
                }
                return $data;
            });
    }
}
