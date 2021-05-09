<?php

namespace LocalheroPortal\LLI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoogleCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gcid' => $this->gcid,
            'name' => $this->name
        ];
    }
}
