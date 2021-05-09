<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Models\GoogleBusinessCategory;

class TransferOldLocationCategories extends Seeder
{
    public function run()
    {
        $categories = DB::table('location_categories')->get();
        $categories->each(function ($category, $key) {
            GoogleBusinessCategory::create(
                ['id' => $category->id,
                'gcid' => $category->foreign_key,
                'name' => $category->name]

            );
        });

    }
}
