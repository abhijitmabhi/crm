<?php

namespace LocalheroPortal\Core\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use LocalheroPortal\Models\LLI\Company;

class MapPinCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [];
        $this->collection->each(function ($pin, $key) use (&$response) {
            $type =  (new \ReflectionClass($pin))->getShortName();
            if ($type == "Lead") {
                $response[$key]['status'] = $pin->status;
            }
            if ($type == "Lead" && $pin->expert_id != Auth::id()) {
                $response[$key]['blocked'] = $pin->blocked;
                if ($pin->blocked) {
                    $response[$key]['expert'] = $pin->expert->name;
                    $response[$key]['company_name'] = $pin->company_name;
                }
            }
            $response[$key]['id'] = $pin->id;
            $response[$key]['coordiantes'] = $pin->coordinates;
            $response[$key]['type'] = $type;
        });
        return $response;
    }
}
