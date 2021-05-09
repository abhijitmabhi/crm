<?php

namespace LocalheroPortal\LLI\Feature\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $exclude = [
            'created_at',
            'updated_at',
            'last_synced',
            'google_business_key',
            'category_id',
            'geo_coordinates',
            // 'payment_methods',
        ];
        $response = [];
        $model_attributes = parent::toArray($request);
        foreach ($model_attributes as $name => $value) {
            if (!in_array($name, $exclude)) {
                $response[$name] = $value;
            }
        }
        //TODO: change back
//        $category = LocationCategory::find($this->category_id);
//        $response['category'] = [
//            'foreign_key'     => $category->foreign_key,
//            'name'            => $category->name,
//        ];
        return $response;
    }
}
