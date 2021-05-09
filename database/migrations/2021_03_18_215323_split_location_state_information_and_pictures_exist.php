<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class SplitLocationStateInformationAndPicturesExist extends Migration
{

    public function up()
    {
        Artisan::call('db:seed', [
            '--class' => 'SplitLocationStateInformationAndPicturesExist',
            '--force' => true
        ]);
    }
}
