<?php

namespace LocalheroPortal\Callcenter\Console\Commands;

use Illuminate\Console\Command;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;

class FixRecalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callcenter:fixrecallagents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes Leads that are recalls without assigned agent, by searching the comments for the user who set the recall';

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
        $leads = Lead::recall()->whereNull('agent_id')->get();
        $leads->each(function (Lead $lead) {
            $comment = $lead->comments()->whereReason(CommentReason::RECALL)->orderBy('created_at')->first();
            if ($comment) {
                $lead->agent_id = $comment->user_id;
                $lead->save();
            }
        });
    }
}
