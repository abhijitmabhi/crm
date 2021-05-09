<?php

namespace LocalheroPortal\Core\Console\Commands;

use Illuminate\Console\Command;
use Faker\Generator as Faker;
use LocalheroPortal\Callcenter\Jobs\FetchLeadCoordinates;
use LocalheroPortal\Models\Lead;

class BackfillLeadCoordinates extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches coordinates for all leads in DB';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backfill:lead-coordinates';

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
    public function handle(Faker $faker)
    {
        Lead::chunk(50, function ($leads) {
            foreach ($leads as $lead) {
                if ($this->needs_no_fetching($lead)) {
                    continue;
                }
                FetchLeadCoordinates::dispatch($lead);
            }
        });
    }

    protected function needs_no_fetching($lead) {
        return !empty($lead->coordinates) && !empty('place_id');
    }
}
