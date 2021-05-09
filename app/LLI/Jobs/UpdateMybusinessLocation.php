<?php

namespace LocalheroPortal\LLI\Jobs;

use Google_Service_MyBusiness_BusinessHours;
use Google_Service_MyBusiness_Category;
use Google_Service_MyBusiness_Date;
use Google_Service_MyBusiness_Location;
use Google_Service_MyBusiness_PostalAddress;
use Google_Service_MyBusiness_Profile;
use Google_Service_MyBusiness_SpecialHourPeriod;
use Google_Service_MyBusiness_SpecialHours;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\LLI\Traits\HasMybusinessService;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Utils\BusinessHoursMapper;

class UpdateMybusinessLocation extends BaseJob
{
    use HasMybusinessService;

    /**
     * @var \LocalheroPortal\Models\LLI\Company
     */
    protected $company;

    /**
     * @var \Google_Service_MyBusiness_Location
     */
    protected $googleLocation;

    /**
     * @var \LocalheroPortal\Models\LLI\Location
     */
    protected $location;

    /**
     * @var array
     */
    protected $paymentOptionsLookup = [
        'NFC'        => 'pay_mobile_nfc',
        'DEBIT_CC'   => 'pay_debit_card',
        'VISA'       => 'visa',
        'MASTERCARD' => 'mastercard',
        'CASH'       => 'requires_cash_only',
        'cheque'     => 'pay_check',
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Company $company, Location $location)
    {
        $this->location = $location;
        $this->company = $company;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if (config('api_settings.read_only') || !config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $this->init_google_service_mybusiness();
        $this->googleLocation = new Google_Service_MyBusiness_Location();
        $this->set_core_data();
        $this->set_opening_hours();
        $this->set_special_hours();
        $this->set_address_data();

        try {
            $this->googleMyBusinessService->accounts_locations->patch(
                $this->location->google_business_key,
                $this->googleLocation,
                [
                    'validateOnly' => false,
                    "updateMask"   => implode(",", Config::get('api_settings.fields')),
                ]
            );
            $this->location->last_synced = now('Europe/Berlin')->toDateTimeString();
            $this->location->save();
        } catch (\Exception $e) {
            Log::error(
                'Can not update location' . PHP_EOL
                . 'company_id: ' . $this->company->id . PHP_EOL
                . str_repeat('#', 20) . PHP_EOL
                . $e->getMessage()
                . str_repeat('#', 20) . PHP_EOL
            );
        }
        $this->upload_images();
    }

    private function set_address_data(): void
    {
        $location = $this->location;
        $googleLocation = $this->googleLocation;

        $address = new Google_Service_MyBusiness_PostalAddress();
        $address->setPostalCode($location->postcode);
        $address->setLocality($location->city);
        $address->setRegionCode($location->country);
        $address->setLanguageCode('de');
        $addressLines = [];

        if (!empty($location->address)) {
            $addressLines[] = $location->address;
        }
        if (!empty($location->address_addition)) {
            $addressLines[] = $location->address_addition;
        }
        $address->setAddressLines($addressLines);

        $googleLocation->setAddress($address);
    }

    private function set_core_data(): void
    {
        $location = $this->location;
        $googleLocation = $this->googleLocation;
        $categoryArray = [];

        $googleLocation->setLanguageCode('de');
        $googleLocation->setLocationName($location->name);

        if (!empty($location->website)) {
            $googleLocation->setWebsiteUrl($location->website);
        } else {
            $googleLocation->setWebsiteUrl($this->company->url);
        }

        $primaryCategory = new Google_Service_MyBusiness_Category();
        $primaryCategory->setCategoryId($this->location->mainCategories()->first()->gcid);
        $googleLocation->setPrimaryCategory($primaryCategory);

        $googleLocation->setPrimaryPhone($location->phone);

        if (!empty($location->additionalCategories)) {
            foreach ($location->additionalCategories as $additionalCategory) {
                $category = new Google_Service_MyBusiness_Category();
                $category->setCategoryId($additionalCategory->gcid);
                $categoryArray[] = $category;
            }
            $googleLocation->setAdditionalCategories($categoryArray);
        }

        if (!empty($location->short_description)) {
            $profile = new Google_Service_MyBusiness_Profile();
            $profile->setDescription($location->short_description);
            $googleLocation->setProfile($profile);
        }
    }

    /**
     * @param mixed $storedPeriod
     */
    private function set_date($storedPeriod, string $type)
    {
        $date = new Google_Service_MyBusiness_Date();
        $date->setYear($storedPeriod->{$type}->year);
        $date->setMonth($storedPeriod->{$type}->month);
        $date->setDay($storedPeriod->{$type}->day);
        return $date;
    }

    private function set_opening_hours(): void
    {
        $location = $this->location;
        $googleLocation = $this->googleLocation;

        $openingHours = new Google_Service_MyBusiness_BusinessHours();
        $generatedOpeningHours = [];

        $storedOpeningHours = json_decode($location->openinghours, true);
        if (is_array($storedOpeningHours)) {
            $generatedOpeningHours = BusinessHoursMapper::mapGoogleBusinessModelToLocalModel($storedOpeningHours);
        }
        $openingHours->setPeriods($generatedOpeningHours);
        $googleLocation->setRegularHours($openingHours);
    }

    private function set_payment_options(): void
    {
        if ($this->location->payment_methods) {
        }
    }

    private function set_special_hours(): void
    {
        $periods = [];
        $specialHours = new Google_Service_MyBusiness_SpecialHours();
        foreach (json_decode($this->location->special_openinghours) as $storedPeriod) {
            $period = new Google_Service_MyBusiness_SpecialHourPeriod();
            $period->setStartDate($this->set_date($storedPeriod, 'startDate'));
            $period->setEndDate($this->set_date($storedPeriod, 'endDate'));
            $period->setOpenTime($storedPeriod->openTime);
            $period->setCloseTime($storedPeriod->closeTime);
            $periods[] = $period;
        }
        $specialHours->setSpecialHourPeriods($periods);
        $this->googleLocation->setSpecialHours($specialHours);
    }

    private function upload_images(): void
    {
        $mediaList = $this->googleMyBusinessService->accounts_locations_media->listAccountsLocationsMedia($this->location->google_business_key);
        $names = [];
        foreach ($mediaList as $mediaItem) {
            $names[] = $mediaItem->getName();
        }
        $names = collect($names);
        $localPhotos = $this->location->mediaItems;

        $toReUpload = $localPhotos->filter(function ($value, $key) use ($names) {
            return !$names->contains($value->google_name);
        });

        $toUpload = $this->location->mediaItems()->whereNull('google_name')->get();

        $toUpload = $toUpload->merge($toReUpload);
        foreach ($toUpload as $imgToUpload) {
            UploadPhotoToGoogle::dispatch($this->company, $this->location, $imgToUpload)->onQueue('api_access');
        }
    }
}
