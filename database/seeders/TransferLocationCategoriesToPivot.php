<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Models\LLI\LocationCategoryType;
use LocalheroPortal\Models\GoogleBusinessCategory;
use LocalheroPortal\Models\LLI\Location;

class TransferLocationCategoriesToPivot extends Seeder
{
    public function run()
    {
        $locations = Location::all();
        $locations->each(function ($location, $key) {
            $category = DB::table('location_categories')->where('id', $location->category_id)->first();
            if($category) {
                $newCategoryId = GoogleBusinessCategory::whereGcid($category->foreign_key)->first()->id;
                $location->categories()->attach($newCategoryId, ['type' => LocationCategoryType::MAIN]);
            }
        });

    }
}
