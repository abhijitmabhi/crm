<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LeadCategoriesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->reduce(function ($response, $item) {
            if ($item->category) {
                $response[] = $item->category;
            }
            return $response;
        }, []);
    }
}
