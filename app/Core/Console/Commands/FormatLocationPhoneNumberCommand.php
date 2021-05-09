<?php

namespace LocalheroPortal\Core\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use libphonenumber\NumberParseException;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\LLI\Location;

class FormatLocationPhoneNumberCommand extends Command
{

    protected $description = 'Formats location phone numbers.';
    protected $signature = 'location:format-phone {showId?} {showPhone?} {showError?}';

    public function handle()
    {
        $showId = $this->argument('showId') === 'true';
        $showPhone = $this->argument('showPhone') === 'true';
        $showError = $this->argument('showError') === 'true';
        foreach (Location::withTrashed()->cursor() as $location) {
            try {
                if ($location->phone) {
                    $location->phone = PhoneUtil::formatPhoneNumber($location->phone);
                }
            } catch (NumberParseException $e) {
                $errorMessage = 'Error while parsing location.';
                if ($showId) {
                    $errorMessage = $errorMessage . ' Id: ' . $location->id;
                }
                if ($showPhone) {
                    $errorMessage = $errorMessage . ' phone: ' . $location->phone;
                }
                $this->error($errorMessage);
                if ($showError) {
                    $this->error($e->getMessage());
                }
            }
            try {
                if ($location->mobilephone) {
                    $location->mobilephone = PhoneUtil::formatPhoneNumber($location->mobilephone);
                }
            } catch (NumberParseException $e) {
                $errorMessage = 'Error while parsing location.';
                if ($showId) {
                    $errorMessage = $errorMessage . ' Id: ' . $location->id;
                }
                if ($showPhone) {
                    $errorMessage = $errorMessage . ' phone: ' . $location->mobilephone;
                }
                $this->error($errorMessage);
                if ($showError) {
                    $this->error($e->getMessage());
                }
            }
            try {
                $location->save();
            } catch (Exception $e) {
                $errorMessage = 'Error while saving location.';
                if ($showId) {
                    $errorMessage = $errorMessage . ' Id: ' . $location->id;
                }
                if ($showPhone) {
                    $errorMessage = $errorMessage . ' phone: ' . $location->phone;
                }
                $this->error($errorMessage);
                if ($showError) {
                    $this->error($e->getMessage());
                }
            }
        }
    }
}
