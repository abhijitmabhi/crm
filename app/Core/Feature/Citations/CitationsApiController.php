<?php

namespace LocalheroPortal\Core\Feature\Citations;

use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\LLI\Feature\Location\LocationValidationUseCase;
use LocalheroPortal\Models\LLI\Location;

class CitationsApiController extends Controller
{
    public function update(Location $location, Request $request)
    {
        foreach($request->citationSources as $source) {
            $newState = $source['pivot']['state'];
            $citationSourceId = $location
                ->activeCitations()
                ->wherePivot('location_id', $source['pivot']['location_id'])
                ->wherePivot('citation_source_id', $source['pivot']['citation_source_id'])
                ->first()
                ->id;
            $location->activeCitations()->updateExistingPivot($citationSourceId, ['state' => $newState]);
        }

        $validationUseCase = new LocationValidationUseCase($location);
        $validationUseCase->onCitationSourcesChanged();
        $location->save();
    }

}
