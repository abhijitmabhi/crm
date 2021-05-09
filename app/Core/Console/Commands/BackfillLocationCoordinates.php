<?php

namespace LocalheroPortal\Core\Console\Commands;

use Illuminate\Console\Command;
use LocalheroPortal\Core\Jobs\FetchCoordinates;
use LocalheroPortal\Models\LLI\Location;

class BackfillLocationCoordinates extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches coordinates for all locations in DB';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backfill:location-coordinates';

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
        Location::chunk(50, function ($locations) {
            foreach ($locations as $location) {
                /**
                 * @var Location $location
                 */
                FetchCoordinates::dispatch($location->id, get_class($location), $location->name . ' ' . $location->address . ' ' . $location->postcode . ' ' . $location->city);
            }
        });
    }
}