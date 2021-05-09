<?php

namespace LocalheroPortal\Callcenter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use LocalheroPortal\Callcenter\Events\LeadCoordinatesFetched;
use LocalheroPortal\Models\Lead;
use Spatie\Geocoder\Facades\Geocoder;

class FetchLeadCoordinates implements ShouldQueue
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
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (App::environment('production')) {
            $lead = $this->lead;
            $coordinates = Geocoder::getCoordinatesForAddress("$lead->company_name, {$lead->street}, {$lead->city}");
            $lead->coordinates = [
                'lat' => $coordinates['lat'],
                'lng' => $coordinates['lng']
            ];
            if (isset($coordinates['place_id'])) {
                $lead->place_id = $coordinates['place_id'];
            }
            $lead->save();
            //TODO: fix after bugfix
//        FetchMapScreenshot::dispatch($lead->id);
            event(new LeadCoordinatesFetched());
        }
    }
}