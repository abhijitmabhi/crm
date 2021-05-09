<?php


namespace LocalheroPortal\Utils;


class BusinessHoursMapper
{

    public static function mapLocalModelToGoogleBusinessHours($storedOpeningHours){
        $generatedOpeningHours = [];
        foreach ($storedOpeningHours as $storedOpeningHour) {
            foreach ($storedOpeningHour[key($storedOpeningHour)] as $array){
                $generatedOpeningHours[] = [
                    'openDay' => strtoupper(key($storedOpeningHour)),
                    'closeDay' => strtoupper(key($storedOpeningHour)),
                    'openTime' => $array['open'],
                    'closeTime' => $array['close'],
                ];
            }
        }
        return $generatedOpeningHours;
    }

    public static function mapGoogleBusinessModelToLocalModel($hoursFromGoogle)
    {
        $resultObject = (object)[
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
            'sunday' => [],
        ];

        foreach ($hoursFromGoogle as $googleHour) {
            $new_arr_key = strtolower($googleHour['closeDay']);
            $resultObject->{$new_arr_key} [] = [
                'open' => str_replace(':', '', substr($googleHour['openTime'], 11, 5)), // check format
                'close' => str_replace(':', '', substr($googleHour['closeTime'], 11, 5)), // check format
                'isOpen' => isset($googleHour['openTime']),
                'id' => uniqid()
            ];
        }
        return $resultObject;
    }

}
