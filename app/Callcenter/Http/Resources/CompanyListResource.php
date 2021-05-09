<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CompanyListResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($company) use (&$response) {
            $locations = [];
            $company->locations()->get()->map(function ($location) use (&$locations) {
                $locations[] = $location->id;
            });
            $resource = [
                'email'           => $company->email,
                'id'              => $company->id,
                'locations'       => $locations,
                'name'            => $company->name,
                'phone'           => $company->phone,
                'service_package' => $company->service_package,
                'url'             => $company->url,
                'hasAuth'         => !!$company->googleAuth,
                'zip'             => $company->zip,
                'city'            => $company->city,
                'street'          => $company->street,
            ];
            if (isset($this->logo)) {
                $resource['logo'] = Storage::disk('companyFiles')->url($company->logo);
            }
            return $resource;
        });
    }
}
