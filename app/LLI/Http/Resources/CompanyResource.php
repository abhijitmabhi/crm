<?php

namespace LocalheroPortal\LLI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $locations = [];
        $this->locations()->get()->map(function ($location) use (&$locations) {
            $locations[] = $location->id;
        });
        $resource = [
            'email'           => $this->email,
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'locations'       => $locations,
            'name'            => $this->name,
            'phone'           => $this->phone,
            'service_package' => $this->service_package,
            'url'             => $this->url,
            'hasAuth'         => !!$this->googleAuth,
            'zip'             => $this->zip,
            'city'            => $this->city,
            'street'          => $this->street,
            'google_auth'     => $this->googleAuth()->get(),
            'user_id'         => $this->user_id
        ];
        if (isset($this->logo)) {
            $resource['logo'] = Storage::disk('s3')->url($this->logo);
        }
        return $resource;
    }
}
