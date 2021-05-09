<?php

namespace LocalheroPortal\LLI\Feature\Location\Image;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Models\LLI\Location;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $locationRepo = new LocationRepository();
        return [
            'id'                   => $this->id,
            'company_id'           => $locationRepo->getById($this->location_id)->company()->get()->first()->id,
            'location_id'          => $this->location_id,
            'location_association' => $this->location_association,
            'url'                  => Storage::disk('s3')->url($this->thumbnail_path),
            'original'             => Storage::disk('s3')->url($this->filename),
            'height'               => $this->height,
            'width'                => $this->width,
            'ratio'                => $this->ratio
        ];
    }
}
