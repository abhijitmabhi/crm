<?php

namespace LocalheroPortal\Core\Feature\ProblemLocations;

use LocalheroPortal\Core\Feature\ClickUp\ClickUpEmailList;
use LocalheroPortal\Core\Feature\ClickUp\ClickUpService;
use LocalheroPortal\Core\Feature\ClickUp\MailableClickUpTask;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;

class TagProblemLocationUseCase
{

    public Location $location;
    public String $taskTemplate;

    public function __construct(Location $location, String $taskTemplate)
    {
        $this->location = $location;
        $this->taskTemplate = $taskTemplate;
    }

    public function tagLocationAsProblematic(): void
    {
        $this->location->addState(LocationState::HAS_PROBLEM);
        $this->location->save();
        $this->createClickUpTask();
    }

    private function createClickUpTask(): void
    {
        $taskData = [
            'locationId' => $this->location->id,
            'locationName' => $this->location->name,
            'locationAddress' => $this->location->address,
            'locationZip' => $this->location->postcode
        ];

        $clickUpService = new ClickUpService();
        $subject = "Location \"" . $taskData['locationName'] . "\" hat ein Problem!";
        $clickUpTask = new MailableClickUpTask($subject, $taskData, $this->taskTemplate);
        $clickUpService->createClickUpTask($clickUpTask, ClickUpEmailList::SUPPORT);
    }

}