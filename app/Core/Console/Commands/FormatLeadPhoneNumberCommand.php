<?php

namespace LocalheroPortal\Core\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use libphonenumber\NumberParseException;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\Lead;

class FormatLeadPhoneNumberCommand extends Command
{

    protected $description = 'Formats lead phone numbers.';
    protected $signature = 'lead:format-phone {showId?} {showPhone?} {showError?}';

    public function handle()
    {
        $showId = $this->argument('showId') === 'true';
        $showPhone = $this->argument('showPhone') === 'true';
        $showError = $this->argument('showError') === 'true';
        foreach (Lead::withTrashed()->cursor() as $lead) {
            try {
                $lead->phone1 = PhoneUtil::formatPhoneNumber($lead->phone1);
            } catch (NumberParseException $e) {
                $errorMessage = 'Error while parsing lead.';
                if ($showId) {
                    $errorMessage = $errorMessage . ' Id: ' . $lead->id;
                }
                if ($showPhone) {
                    $errorMessage = $errorMessage . ' phone: ' . $lead->phone1;
                }
                $this->error($errorMessage);
                if ($showError) {
                    $this->error($e->getMessage());
                }
            }
            try {
                $lead->save();
            } catch (Exception $e) {
                $errorMessage = 'Error while saving lead.';
                if ($showId) {
                    $errorMessage = $errorMessage . ' Id: ' . $lead->id;
                }
                if ($showPhone) {
                    $errorMessage = $errorMessage . ' phone: ' . $lead->phone1;
                }
                $this->error($errorMessage);
                if ($showError) {
                    $this->error($e->getMessage());
                }
            }
        }
    }
}
