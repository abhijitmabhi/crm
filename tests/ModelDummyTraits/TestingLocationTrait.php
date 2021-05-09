<?php

namespace Tests\ModelDummyTraits;

use LocalheroPortal\LLI\Repository\LocationCategoriesRepository;
use LocalheroPortal\Models\LLI\Location;

trait TestingLocationTrait
{
    use TestingCompanyTrait;

    private $company;

    public function createTestLocation() {
        $this->company = $this->createTestCompany();

        $repo = new LocationCategoriesRepository();

        $location = new Location;
        $location->company_id = $this->company->id;
        $location->address = "TestAdress 10";
        $location->postcode = "75365";
        $location->city = "TestCity";
        $location->category_id = $repo->getFirstExistingCategoryId();
        $location->save();

        return $location;
    }

    public function deleteTestLocation(Location $location) {
        $location->forceDelete();
        $this->deleteTestCompany($this->company);
    }
}
