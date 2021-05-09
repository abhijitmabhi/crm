<?php

namespace LocalheroPortal\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LocalheroPortal\Models\Lead;
use Spatie\Geocoder\Facades\Geocoder;

class FetchCoordinates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Lead
     */
    private $model;

    private $adress;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id, string $classname, string $adress)
    {
        $this->model = $classname::find($id);
        $this->adress = $adress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = $this->model;
        $coordinates = Geocoder::getCoordinatesForAddress($this->adress);
        $model->coordinates = collect($coordinates)->only('lat', 'lng')->toArray();
        $model->place_id = $coordinates['place_id'];
        $model->save();
    }
}