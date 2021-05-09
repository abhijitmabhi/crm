<?php

namespace LocalheroPortal\LLI\Jobs;

use Google_Service_MyBusiness_LocationAssociation;
use Google_Service_MyBusiness_MediaItem;
use Illuminate\Support\Facades\Log;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationMediaItem;
use LocalheroPortal\LLI\Traits\HasMybusinessService;

class UploadPhotoToGoogle extends BaseJob
{
    use HasMybusinessService;

    /**
     * @var \LocalheroPortal\Models\LLI\Company;
     */
    protected $company;

    /**
     * @var \LocalheroPortal\Models\LLI\Location;
     */
    protected $location;

    /**
     * @var \LocalheroPortal\Models\LLI\LocationMediaItem
     */
    protected $mediaItem;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Company $company, Location $location, LocationMediaItem $mediaItem)
    {
        $this->company = $company;
        $this->mediaItem = $mediaItem;
        $this->location = $location;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if (config('api_settings.read_only') || !config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $this->init_google_service_mybusiness();
        $access_token = $this->company->googleauth->getAccessToken();
        $sumr = new \Google_Service_MyBusiness_StartUploadMediaItemDataRequest();
        $mediaItemDataRef = $this->googleMyBusinessService->accounts_locations_media->startUpload($this->location->google_business_key, $sumr);

        $url = "https://mybusiness.googleapis.com/upload/v1/media/{$mediaItemDataRef->getResourceName()}?upload_type=media";
        $headers = [];
        $headers[] = "Authorization: Bearer " . $access_token;
        $headers[] = "Content-Type: application/json";
        $file = \Storage::disk('s3')->get($this->mediaItem->filename);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
        curl_setopt($ch, CURLOPT_POSTREDIR, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $r = curl_getinfo($ch);
        if ($r["http_code"] != 200) {
            $detais = json_decode($result, true);
            if (isset($detais["msg"])) {
                Log::error($detais["msg"]);
            } else {
                Log::error("HTTP Return " . $r["http_code"]);
            }
        }
        curl_close($ch);

        $photo = new Google_Service_MyBusiness_MediaItem();
        $photoAssociation = new Google_Service_MyBusiness_LocationAssociation();
        $photoAssociation->setCategory($this->mediaItem->location_association);
        $photo->setLocationAssociation($photoAssociation);
        $photo->setDataRef($mediaItemDataRef);
        $photo->setMediaFormat('PHOTO');
        try {
            $uploaded = $this->googleMyBusinessService->accounts_locations_media->create($this->location->google_business_key, $photo);
            $this->mediaItem->google_name = $uploaded->getName();
            $this->mediaItem->save();
        } catch (\Exception $e) {
            Log::error('Can not create image ' . \Storage::disk('locationImages')->url($this->mediaItem->filename) . ' message: ' . $e->getMessage());
        }
    }
}
