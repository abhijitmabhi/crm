<?php

namespace LocalheroPortal\Core\Console\Commands;

use Illuminate\Console\Command;
use LocalheroPortal\Callcenter\Jobs\FetchMapScreenshot;
use LocalheroPortal\Models\Lead;

class BackfillLeadScreenshots extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches screenshots for all leads in DB';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backfill:lead-screenshots';

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

        foreach (Lead::cursor() as $lead) {
            /**
             * @var Lead $lead
             */
            if ($lead->hasMedia('leads')) {
                continue;
            }
            //TODO: enable after bugfix
//            FetchMapScreenshot::dispatch($lead->id);
        }
    }
}