<?php

namespace LocalheroPortal\Callcenter\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use LocalheroPortal\Callcenter\Events\PushRecallNotification;
use LocalheroPortal\Models\Lead;

class SendRecallNotifications extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications for upcoming recalls';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callcenter:send-recall-notifications';

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
        $now = new CarbonImmutable();
        foreach (Lead::recall()->whereBetween('closed_until', [$now, $now->addMinutes(10)])
            ->cursor() as $lead) {
            if (Cache::missing("sentNotification.$lead->id")) {
                event(new PushRecallNotification($lead->id));
                Cache::put("sentNotification.$lead->id", true, 10000);
            }
        }
    }
}