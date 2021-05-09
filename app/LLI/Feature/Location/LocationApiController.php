<?php

namespace LocalheroPortal\LLI\Feature\Location;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\LLI\Jobs\UpdateMybusinessLocation;
use LocalheroPortal\Models\GoogleBusinessCategory;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\LLI\CitationSource;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationCategoryType;
use LocalheroPortal\Models\LLI\RankQuery;
use Carbon\Carbon;

class LocationApiController extends Controller
{

    public function index(Company $company)
    {
        return LocationResource::collection($company->locations);
    }

    public function show(Company $company, Location $location)
    {
        return new LocationResource($location);
    }

    public function store(Company $company, LocationRequest $request)
    {
        $location = new Location($request->except('rank_queries', 'mainCategory', 'additionalCategories', 'selectedCitationCatagories'));
        $location->phone = PhoneUtil::formatPhoneNumber($location->phone);
        $hasConflict = Location::wherePhone($location->phone)->exists();
        if ($hasConflict) {
            return Response::json(['errors' => [__('validation.custom.phone.unique_location_phone')]], 409);
        }

        $hasConflict = Lead::wherePhone1($location->phone)->where('status', LeadState::CLOSED)->exists();
        if ($hasConflict) {
            return Response::json(['errors' => [__('validation.custom.phone.unique_lead_phone')]], 409);
        }
        $lead = Lead::wherePhone1($location->phone)->first();
        if ($lead == null) {
            $mapper = new LocationToLeadMapper();
            $lead = $mapper->mapLocationToLead($location);
        }

        DB::beginTransaction();
        try {
            $lead->status = LeadState::CLOSED;
            $lead->save();
            $location->lead_id = $lead->id;


            if ($location->mobilephone) {
                $location->mobilephone = PhoneUtil::formatPhoneNumber($location->mobilephone);
            }
            $location->states = array();

            if ($request->openinghours) {
                $location->openinghours = $request->openinghours;
            }

            $company->locations()->save($location);

            $this->updateMainCategory($location, $request->mainCategory);
            if ($request->additionalCategories) {
                $this->updateAdditionalCategories($location, $request->additionalCategories);
            }
            $this->updateLocationCitationCategories($location, $request->selectedCitationCategories);
            $this->updateRankQueries($location, $request->rank_queries);
            $this->updateLocationState($location);
            $location->save();
        } catch (Exception $exception) {
            DB::rollBack();
            return Response::json(['errors' => ['Speichern fehlgeschlagen.']], 500);
        }
        DB::commit();

        //TODO: trigger background jobs
        return Response::json(['message' => 'Created Location', 'data' => new LocationResource($location)]);
    }

    public function update(Company $company, Location $location, LocationRequest $request)
    {
        //TODO Review: Warum nicht hier eine Transaction draus machen wie bei store?
        $location->fill($request->except(['rank_queries', 'mainCategory', 'additionalCategories', 'selectedCitationCatagories']));
        $location->phone = PhoneUtil::formatPhoneNumber($location->phone);
        $hasConflict = Location::wherePhone($location->phone)->where('id', '!=', $location->id)->exists();
        if ($hasConflict) {
            abort(409, _('validation.custom.phone.unique_location_phone'));
        }
        if ($location->mobilephone) {
            $location->mobilephone = PhoneUtil::formatPhoneNumber($location->mobilephone);
        }

        if ($request->openinghours) {
            $location->openinghours = $request->openinghours;
        }

        $this->updateMainCategory($location, $request->mainCategory);
        $this->updateAdditionalCategories($location, $request->additionalCategories);
        $this->updateLocationCitationCategories($location, $request->selectedCitationCategories);
        $this->updateRankQueries($location, $request->rank_queries);

        $this->updateLocationState($location);
        $location->save();

        UpdateMybusinessLocation::dispatch($company, $location);

        return Response::json(['message' => 'Updated Location', 'data' => new LocationResource($location)]);
    }

    private function updateMainCategory(Location $location, string $mainCategory): void
    {
        $location->mainCategories()->detach();
        $locationCategory = GoogleBusinessCategory::whereName($mainCategory)->first();
        $location->categories()->attach($locationCategory->id, ['type' => LocationCategoryType::MAIN]);
    }

    private function updateAdditionalCategories(Location $location, array $updatedAdditionalCategories): void
    {
        $currentAdditionalCategories = $location->additionalCategories()->pluck('name')->toArray();
        $diff = array_diff($currentAdditionalCategories, $updatedAdditionalCategories);
        foreach ($diff as $category) {
            $locationCategory = GoogleBusinessCategory::whereName($category)->first();
            $location->categories()->detach($locationCategory->id);
        }

        foreach ($updatedAdditionalCategories as $category) {
            $locationCategory = GoogleBusinessCategory::whereName($category)->first();
            $location->categories()->syncWithoutDetaching([$locationCategory->id => ['type' => LocationCategoryType::ADDITIONAL]]);
        }

    }

    private function updateLocationCitationCategories(Location $location, array $selectedCitationCategories): void
    {
            foreach($selectedCitationCategories as $category) {
                $sourceIds = CitationSource::whereCategory($category)->withTrashed()->pluck('id')->toArray();
                foreach($sourceIds as $sourceId) {
                    $location->allCitations()->newPivotQuery()->updateOrInsert(
                        ['location_id' => $location->id, 'citation_source_id' => $sourceId],
                        ['deleted_at' => null, 'updated_at' => Carbon::now()]);
                }
            }

            $activeCategories = array_unique($location->activeCitations()->pluck('category')->toArray());
            $toDelete = array_diff($activeCategories, $selectedCitationCategories);
            foreach ($toDelete as $category) {
                $pivotIds = $location->activeCitations()->where('category', '=', $category)->allRelatedIds();
                foreach($pivotIds as $id) {
                    $location->activeCitations()->updateExistingPivot($id, ['deleted_at' => Carbon::now()]);
                }
            }
    }

    private function updateRankQueries(Location $location, array $rankQueries): void
    {
        if (!is_null($rankQueries)) {
            foreach ($rankQueries as $term) {
                RankQuery::withTrashed()->updateOrCreate(
                    ['keyword' => $term, 'location_id' => $location->id],
                    ['deleted_at' => null]
                );
            }

            $active = $location->rankQueries()->pluck('keyword')->toArray();
            $toDelete = array_diff($active, $rankQueries);
            foreach ($toDelete as $item) {
                $location->rankQueries()->where('keyword', '=', $item)->delete();
            }
        }
    }

    private function updateLocationState(Location $location): void
    {
        $validationUseCase = new LocationValidationUseCase($location);
        $validationUseCase->onInformationChanged();
        $validationUseCase->onCitationSourcesChanged();
    }
}
