<?php


namespace LocalheroPortal\LLI\Repository;

use LocalheroPortal\Models\GoogleBusinessCategory;

class LocationCategoriesRepository
{
    public function getFirstExistingCategoryId() {
        return GoogleBusinessCategory::first()->value('id');
    }
}