<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LocalheroPortal\Models\User\Role;

class DataScraperRoleSeeder extends Seeder
{

    public function run()
    {
        Role::create([
            'name' => 'LLI_DATA_SCRAPER',
            'permissions' => ['SCRAPE_MY_BUSINESS'],
            'display_name' => 'Data Scraper'
        ]);
    }
}
