<?php

namespace LocalheroPortal\Callcenter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LocalheroPortal\Models\Lead;

class FetchMapScreenshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Lead
     */
    private $lead;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $leadId)
    {
        $this->lead = Lead::withTrashed()->find($leadId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lead = $this->lead;
        $coordinates = implode(",", $lead->coordinates);
        $key = env('GOOGLE_API_FRONTEND_KEY', '');
        $lead->addMediaFromUrl("https://maps.googleapis.com/maps/api/staticmap?center=$coordinates&zoom=18&size=600x300&maptype=roadmap&markers=color:red%7C$coordinates&key=$key")
            ->usingName('location')
            ->toMediaCollection('leads');
    }
}