<?php

namespace LocalheroPortal\LLI\Jobs;

use Exception;
use Google_Service_MyBusiness_Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\LLI\Events\LocationImported;
use LocalheroPortal\Models\GoogleBusinessCategory;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\LLI\Traits\HasMybusinessService;
use LocalheroPortal\Utils\BusinessHoursMapper;

class DownloadLocationFromGoogle extends BaseJob
{
    use HasMybusinessService;

    /**
     * @var \LocalheroPortal\Models\LLI\Company;
     */
    protected $company;

    /**
     * @var \Google_Service_MyBusiness_Location
     */
    protected $googleLocation;

    /**
     * @var \LocalheroPortal\Models\LLI\Location;
     */
    protected $location;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($googleLocationObject, Company $company)
    {
        $this->googleLocation = $googleLocationObject;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $this->init_google_service_mybusiness();
        $googleLocation = $this->googleLocation;
        $this->location = $location = new Location();
        $location->company_id = $this->company->id;
        $location->name = $googleLocation->locationName;

        if (!is_null($profile = $googleLocation->getProfile())) {
            $location->short_description = $profile->getDescription();
        }

        $this->set_address();
        $this->set_opening_hours();
        $this->set_special_opening_hours();
        $location->phone = $location->mobilephone = $googleLocation->primaryPhone;
        $location->email = $this->company->email;
        $location->google_business_key = $googleLocation->name;

        $category = GoogleBusinessCategory::whereGcid($googleLocation->getPrimaryCategory()->categoryId)->first();
        $location->category_id = $category->id;

        $location->last_synced = now('Europe/Berlin');

        $location->save();
        Cache::forget($location->google_business_key . 'import-status');
        $this->download_photos();
        event(new LocationImported($location));
    }

    public function set_special_opening_hours()
    {
        $attrs = ['startDate', 'openTime', 'endDate', 'closeTime', 'isClosed'];
        if (empty($this->googleLocation->specialHours)) {
            return;
        }
        $specialHours = $this->googleLocation->getSpecialHours();
        $location = $this->location;
        $resultArray = [];
        foreach ($specialHours as $specialHour) {
            $arr = [];
            foreach ($attrs as $attr) {
                if ($specialHour[$attr]) {
                    $arr[$attr] = $specialHour[$attr];
                }
            }
            $resultArray[] = $arr;
        }
        $location->special_openinghours = json_encode($resultArray);
    }

    /**
     * Enqueues a download job for each image associated with the location
     *
     * @return void
     */
    private function download_photos(): void
    {
        try {
            $mediaList = $this->googleMyBusinessService->accounts_locations_media->listAccountsLocationsMedia($this->googleLocation->name);
            foreach ($mediaList as $mediaItem) {
                DownloadPhotoFromGoogle::dispatch($this->company, $this->location, $mediaItem)->onQueue('api_access');
            }
        } catch (Exception $e) {
            Log::channel('lli')->error("Error while fetching mediaList for company {$this->company->id}: " . $e->getMessage());
        }
    }

    /**
     * Sets address data of the location
     *
     * @return void
     */
    private function set_address(): void
    {
        $address = $this->googleLocation->getAddress();
        $location = $this->location;

        $location->address = $address->addressLines[0];
        if (isset($address->addressLines[1])) {
            $location->address_addition = $address->addressLines[1];
        }
        $location->postcode = $address->postalCode;
        $location->city = $address->locality;
        $location->country = $address->regionCode;
    }

    /**
     * Sets opening hours for the Location
     *
     * @return void
     */
    private function set_opening_hours(): void
    {
        if (empty($this->googleLocation->regularHours)) {
            return;
        }

        $regularHours = $this->googleLocation->regularHours->periods;
        $location = $this->location;

        $resultArray = BusinessHoursMapper::mapLocalModelToGoogleBusinessHours($regularHours);

        $location->openinghours = json_encode($resultArray);
    }
}
