<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;

class SplitLocationStateInformationAndPicturesExist extends Seeder
{
    public function run()
    {
        $locations= Location::query()->cursor();
        foreach ($locations as $location) {
            $states = $location->states;
            if (in_array('INFORMATION_AND_PICTURES_EXIST', $states)) {
                $location->removeState('INFORMATION_AND_PICTURES_EXIST');
                $location->addState(LocationState::PICTURES_EXIST);
                $location->addState(LocationState::INFORMATION_EXIST);
                $location->save();
            }
        }
    }
}
