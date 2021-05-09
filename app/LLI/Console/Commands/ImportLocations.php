<?php

namespace LocalheroPortal\LLI\Console\Commands;

use Illuminate\Console\Command;
use LocalheroPortal\Core\Jobs\FetchCoordinates;
use LocalheroPortal\LLI\Jobs\ImportLocations as LocalheroPortalImportLocations;
use LocalheroPortal\Models\LLI\Company;

class ImportLocations extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all Locations from google-mybusiness';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Company::cursor() as $company) {
            /**
             * @var Company $company
             */
            LocalheroPortalImportLocations::dispatch($company)->onQueue('api_access');
        }
    }
}