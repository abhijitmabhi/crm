<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateEventEndSeeder extends Seeder
{
    public function run()
    {
        $cursor = DB::table('calendar_events')->whereNull('event_end')->cursor();
        foreach ($cursor as $event) {
            $new_event_end = Carbon::parse($event->event_begin);
            $new_event_end->addHours(1);
            $data = array('event_end' => $new_event_end);
            DB::table('calendar_events')->where('id', $event->id)->update($data);
        }
    }
}
