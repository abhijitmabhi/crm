<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LocalheroPortal\Models\User\Role;

class RoleDisplayedNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::where('name', 'admin')->update(['display_name' => 'Admin']);
        Role::where('name', 'customer')->update(['display_name' => 'Kunde']);
        Role::where('name', 'manager')->update(['display_name' => 'Manager']);
        Role::where('name', 'UI/UX Designer')->update(['display_name' => 'UI/UX Designer']);
        Role::where('name', 'callcenter-supervisor')->update(['display_name' => 'CallCenter Leiter']);
        Role::where('name', 'callcenter-agent')->update(['display_name' => 'CallCenter Agent']);
        Role::where('name', 'Expert')->update(['display_name' => 'Experte']);
        Role::where('name', 'lli-manager')->update(['display_name' => 'LLI Manager']);
        Role::where('name', 'fix-leads')->update(['display_name' => 'Fix Leads']);
        Role::where('name', 'city')->update(['display_name' => 'Stadt']);


    }
}
