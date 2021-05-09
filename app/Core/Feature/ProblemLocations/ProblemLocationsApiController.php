<?php


namespace LocalheroPortal\Core\Feature\ProblemLocations;

use Illuminate\Routing\Controller;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;

class ProblemLocationsApiController extends Controller
{

    public function reportProblem(Location $location): void
    {
        $taskTemplate = 'lli.location.problemMailClickUpTemplateDataScraper';
        $useCase = new TagProblemLocationUseCase($location, $taskTemplate);
        $useCase->tagLocationAsProblematic();
    }

    public function solveProblem(Location $location): void
    {
        $location->removeState(LocationState::HAS_PROBLEM);
        $location->save();
    }

}