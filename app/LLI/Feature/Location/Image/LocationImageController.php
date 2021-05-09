<?php

namespace LocalheroPortal\LLI\Feature\Location\Image;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use LocalheroPortal\Core\Filters\ImportScaling;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\LLI\Feature\Location\LocationValidationUseCase;
use LocalheroPortal\LLI\Jobs\UpdateMybusinessLocation;
use LocalheroPortal\LLI\Jobs\UploadPhotoToGoogle;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationMediaItem;
use LocalheroPortal\Models\LLI\LocationState;
use function Sentry\captureException;

class LocationImageController extends Controller
{

    public function destroy(Company $company, Location $location, $miId)
    {
        $mi = LocationMediaItem::find($miId);
        if ($mi) {
            Storage::disk('s3')->delete($mi->filename);
            Storage::disk('s3')->delete($mi->thumbnail_path);
            $mi->delete();
            $validationUseCase = new LocationValidationUseCase($location);
            $validationUseCase->onImagesChanged();
            $location->save();
            return Response::json(['success' => 'Image deleted']);
        }
        return Response::json(['error' => 'No Image with ID '.$miId], 422);
    }

    public function index(Company $company, Location $location, Request $request)
    {
        $pagination = $request->per_page ?? 15;
        $sortBy = $request->sort_by ?? 'created_at';
        $images = $location->mediaItems()->orderBy($sortBy, 'desc')->paginate($pagination);
        return ImageResource::collection($images);
    }

    public function show(UploadImageRequest $request, Company $company, Location $location, $miId)
    {
        $mi = LocationMediaItem::find($miId);
        if ($mi) {
            return new ImageResource($mi);
        }
        return Response::json(['error' => 'No item with ID '.$miId]);
    }

    public function store(UploadImageRequest $request, Company $company, Location $location)
    {
        DB::beginTransaction();
        try {
            $prefix = now('Europe/Berlin')->toDateTimeLocalString().'_';
            $requestImage = $request->file('image');
            $fileName = $prefix.$requestImage->getClientOriginalName();
            $originalPath = $location->id.'/images/'.$fileName;
            $hash = md5_file($requestImage);
            $thumbnailPath = $location->id.'/thumbnails/'.$fileName;
            list($width, $height) = getimagesize($requestImage);
            $ratio = $width / $height;
            $image = Image::make($requestImage)->filter(new ImportScaling());
            $image->encode('jpeg');
            Storage::disk('s3')->put($originalPath, $image);
            $image->fit(560, 300)->sharpen(5)->encode('jpeg');
            Storage::disk('s3')->put($thumbnailPath, $image);
            $mediaItem = new LocationMediaItem([
                'filename' => $originalPath,
                'location_association' => $request->location_association ?? 'ADDITIONAL',
                'thumbnail_path' => $thumbnailPath,
                'hash' => $hash,
                'height' => $height,
                'width' => $width,
                'ratio' => $ratio
            ]);
            $location->mediaItems()->save($mediaItem);
            $validationUseCase = new LocationValidationUseCase($location);
            $validationUseCase->onImagesChanged();
            $location->save();
            DB::commit();
            UploadPhotoToGoogle::dispatch($company, $location, $mediaItem);
            return Response::make('Datei hochgeladen', 201);
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::make($e, 500);
        }
    }

    public function update(Request $request, Company $company, Location $location, $miId)
    {
        $mi = LocationMediaItem::find($miId);
        if (!$mi) {
            return Response::make('No Image with Id '.$miId, 422);
        }
        $request->validate([
            'location_association' => 'string|required',
        ]);
        $mi->location_association = $request->location_association;
        try {
            $mi->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        UpdateMybusinessLocation::dispatch($company, $location);
        return Response::make('Image updated', 200);
    }
}
