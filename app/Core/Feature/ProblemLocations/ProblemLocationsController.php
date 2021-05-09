<?php
namespace LocalheroPortal\Core\Feature\ProblemLocations;

use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Models\LLI\LocationState;

class ProblemLocationsController extends Controller
{
    public function showAllLocationsWithProblems()
    {
        $repo = new LocationRepository();
        $problematicLocations = $repo->getByState(LocationState::HAS_PROBLEM);

        return view('lli.location.ProblemLocationsView', ['problematicLocations' => $problematicLocations]);
    }

}
