<?php

namespace LocalheroPortal\Callcenter\Console\Commands;

use Illuminate\Console\Command;
use LocalheroPortal\Models\Lead;

class ResetPipeline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callcenter:reset-pipeline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the "in_pipeline"-Attribute of every lead to 1';

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
        Lead::where('in_pipeline', '=', 0)->each(function (Lead $lead) {
            $lead->unlock();
        });
    }
}
