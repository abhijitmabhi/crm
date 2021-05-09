<?php

namespace LocalheroPortal\LLI\Jobs;

use Exception;
use Google_Service_MyBusiness;
use Google_Service_MyBusiness_Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\LLI\Traits\HasMybusinessService;

class ImportLocations extends BaseJob
{
    use HasMybusinessService;

    /**
     * @var \LocalheroPortal\Models\LLI\Company
     */
    protected $company;

    /**
     * Create a new job instance.
     * @param  Company $company
     * @return void
     */
    public function __construct(Company $company)
    {
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
        try {
            $accounts = $this->googleMyBusinessService->accounts->listAccounts();
            $account_name = $accounts[0]->name;
            $googleLocations = $this->googleMyBusinessService->accounts_locations->listAccountsLocations($account_name)->getLocations();
            foreach ($googleLocations as $location) {
                /**
                 * @var Google_Service_MyBusiness_Location $location
                 */

                Log::channel('lli')->info("Processing {$location->getName()}");
                if ($this->needsLocationImport($location)) {
                    DownloadLocationFromGoogle::dispatch($location, $this->company)->onQueue('api_access');
                    Log::channel('lli')->info("Enqueued Download of location {$location->getName()}");
                }else{
                    Log::channel('lli')->info("Skipping Location: {$location->getName()} ");
                }
            }
        } catch (\Exception $e) {
            die('An error occured: ' . $e->getMessage() . "\n");
        }
    }

    /**
     * @param  Google_Service_MyBusiness_Location $location
     * @return bool
     */
    private function needsLocationImport(Google_Service_MyBusiness_Location $location): bool
    {
        $repo = new LocationRepository();
        $conflictLocation = $repo->getFirstByGoogleBusinessKey($location->getName());
        if ($conflictLocation == null && Cache::missing($location->getName() . 'import-status')) {
            Cache::forever($location->getName() . 'import-status', 'queued for download');
            return true;
        }
        return false;
    }
}