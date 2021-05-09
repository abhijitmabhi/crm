<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateZipSeeder extends Seeder
{
    public function run()
    {
        $cursor = DB::table('leads')->whereNotNull('zip')->cursor();
        foreach ($cursor as $event) {
            if (preg_match('/(?<!^)\D|^[^+\d]/', $event->zip)) {
                $new_zip = preg_replace("/(?<!^)\D|^[^+\d]/", '', $event->zip);
                $data = array('zip' => $new_zip);
                DB::table('leads')->where('id', $event->id)->update($data);
            }
        }
    }
}
