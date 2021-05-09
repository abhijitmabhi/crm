<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MigrateOpeningHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $selectedRows = DB::select("SELECT id, openinghours FROM locations");
        foreach ($selectedRows as $key => $value) {
            $openingHours = $this->migrateOpeningHours(json_decode($value->openinghours, true));
            if(!empty((array)$openingHours)){
                $data = array('openinghours' => json_decode(json_encode($openingHours), true));
                DB::table('locations')->where('id', '=', $value->id)->update($data);
            }
        }
    }

    /**
     * Migrate the data in openinghours in locations table
     * @param $old_opening_hours - the old format is: {"day":"SUNDAY","opening_time":"09:00","closing_time":"21:00"}
     * @return object - tne new format is:  sunday: [{ open: '0900', close: '2100', id: '5ca5578b0c5c7', isOpen: false }]
     */
    public function migrateOpeningHours($old_opening_hours)
    {
        $mappedOpenHoursDays = [
            'MONDAY' => 'monday',
            'TUESDAY' => 'tuesday',
            'WEDNESDAY' => 'wednesday',
            'THURSDAY' => 'thursday',
            'FRIDAY' => 'friday',
            'SATURDAY' => 'saturday',
            'SUNDAY' => 'sunday'
        ];

        $new_opening_hoursObject = (object)[
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
            'sunday' => [],
        ];

        if (isset($old_opening_hours)) {
            foreach ($old_opening_hours as $key => $value) {
                if (isset($value["day"]) || !is_null($value["opening_time"])) {
                    $new_arr_key = strtolower($mappedOpenHoursDays[$value["day"]]);
                    $new_opening_hoursObject->{$new_arr_key} [] = [
                        'open' => str_replace(':','',$value["opening_time"]),
                        'close' => str_replace(':','',$value["closing_time"]),
                        'isOpen' => true,
                        'id' => uniqid()
                    ];
                }
            }
        } else {
            return (object)[];
        }
        return $new_opening_hoursObject;
    }
}
