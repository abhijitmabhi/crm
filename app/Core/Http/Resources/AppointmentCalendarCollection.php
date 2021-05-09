<?php

namespace LocalheroPortal\Core\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use stdClass;

class AppointmentCalendarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($event) use ($request) {
            return (new FullcalendarEventResource($event))->toArray($request);
        });
    }
}
