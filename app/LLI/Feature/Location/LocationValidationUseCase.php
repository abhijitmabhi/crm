<?php


namespace LocalheroPortal\LLI\Feature\Location;

use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationCitationState;
use LocalheroPortal\Models\LLI\LocationState;


class LocationValidationUseCase
{

    private Location $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function onStatisticsChanged(): void
    {
        $hasInsights = $this->location->insights()->exists();
        $hasRankResults = false;
        $this->location->rankQueries->each(function ($rankQuery) use (&$hasRankResults) {
            if ($rankQuery->rankResults()->exists()) {
                $hasRankResults = true;
            }
        });
        $hasKeywordUsageResults = $this->location->keywordUsageResults()->exists();
        if ($hasRankResults && $hasKeywordUsageResults && $hasInsights) {
            $this->location->addState(LocationState::STATISTICS_READY);
        }
    }

    public function onInformationChanged(): void
    {
        if (
            $this->location->name
            && $this->location->address
            && $this->location->postcode
            && $this->location->city
            && $this->location->state
            && $this->location->country
            && $this->location->email
            && $this->location->phone
            && $this->location->website
            && $this->location->description
            && $this->location->rankQueries()->exists()
            && str_contains(json_encode($this->location->openinghours), 'isOpen":true')
            && $this->location->mainCategories()->exists()
        ) {
            $this->location->addState(LocationState::INFORMATION_EXIST);
        } else {
            $this->location->removeState(LocationState::INFORMATION_EXIST);
        }
    }

    public function onImagesChanged(): void
    {
        if ($this->location->mediaItems()->exists()) {
            $this->location->addState(LocationState::PICTURES_EXIST);
        } else {
            $this->location->removeState(LocationState::PICTURES_EXIST);
        }
    }

    public function onCitationSourcesChanged(): void
    {
        if($this->areAllCitationsDone()) {
            $this->location->addState(LocationState::CITATIONS_DONE);
        } else {
            $this->location->removeState(LocationState::CITATIONS_DONE);
        }
    }

    private function areAllCitationsDone(): bool
    {
        $hasCitationsAllDone = true;
        $this->location->activeCitations->each(function ($citationSource) use (&$hasCitationsAllDone) {
            if($citationSource->pivot->state === LocationCitationState::TODO) {
                $hasCitationsAllDone = false;
            }
        });

        return $hasCitationsAllDone;
    }

}