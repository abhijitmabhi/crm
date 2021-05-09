<?php

namespace LocalheroPortal\Core\Feature\Citations;

use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Location;

class CitationsWebController extends Controller
{

    public function show(Location $location)
    {
        $location->load('activeCitations');
        return view('lli.location.CheckCitationsView', ['location' => $location]);
    }
    
}
