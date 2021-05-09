<?php

namespace LocalheroPortal\LLI\Jobs;

use Exception;
use Google_Service_MyBusiness_MediaItem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use LocalheroPortal\Core\Filters\ImportScaling;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationMediaItem;

class DownloadPhotoFromGoogle extends BaseJob
{
    /**
     * @var \LocalheroPortal\Models\LLI\Company
     */
    protected $company;

    /**
     * @var \LocalheroPortal\Models\LLI\Location
     */
    protected $location;

    /**
     * @var \Google_Service_MyBusiness_MediaItem
     */
    protected $mediaItem;

    /**
     * Create a new job instance.
     * @param  Company                             $company
     * @param  Location                            $location
     * @param  Google_Service_MyBusiness_MediaItem $mediaItem
     * @return void
     */
    public function __construct(Company $company, Location $location, Google_Service_MyBusiness_MediaItem $mediaItem)
    {
        $this->company = $company;
        $this->mediaItem = $mediaItem;
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $original = file_get_contents($this->mediaItem->googleUrl);
        $hash = md5($original);
        $interventionObject = Image::make($original);
        $headers = $this->parse_headers($http_response_header);
        $mimeType = $headers['Content-Type'];
        $filetype = basename($mimeType);
        $filename = basename($this->mediaItem->name) . '.' . $filetype;

        $largeImage = $interventionObject->filter(new ImportScaling())->encode($filetype);
        $largeImagePath =  $this->location->id . '/images/' . $filename;
        Storage::disk('s3')->put(
            $largeImagePath,
            $largeImage
        );
        $thumbnail = $interventionObject->fit(560, 300)->sharpen(5)->encode($filetype);
        $thumbnailPath =  $this->location->id . '/thumbnails/' . $filename;
        Storage::disk('s3')->put(
            $thumbnailPath,
            $thumbnail
        );

        $locationMediaItem = new LocationMediaItem([
            'filename'             => $largeImagePath,
            'thumbnail_path'       => $thumbnailPath,
            'google_name'          => $this->mediaItem->getName(),
            'location_association' => $this->mediaItem->getLocationAssociation()->category,
            'location_id'          => $this->location->id,
            'hash'                 => $hash,
        ]);
        try {
            $locationMediaItem->save();
        } catch (Exception $e) {
            $this->fail($e);
        }

    }

    /**
     * @param  array   $headers
     * @return array
     */
    private function parse_headers(array $headers): array
    {
        $parsedHeader = [];
        foreach ($headers as $header) {
            $exploded = explode(':', $header, 2);
            if (isset($exploded[1])) {
                $parsedHeader[trim($exploded[0])] = trim($exploded[1]);
            } elseif (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $header, $out)) {
                $parsedHeader['reponse_code'] = intval($out[1]);
            } else {
                $parsedHeader[] = $header;
            }
        }
        return $parsedHeader;
    }
}
