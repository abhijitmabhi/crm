<?php

namespace LocalheroPortal\Core\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Core\Feature\ProblemLocations\TagProblemLocationUseCase;
use LocalheroPortal\LLI\Traits\HasMybusinessService;
use LocalheroPortal\Models\GoogleBusinessCategory;
use LocalheroPortal\Models\LLI\Company;
use function Sentry\captureException;

class ImportGoogleBusinessCategoriesCommand extends Command
{
    use HasMybusinessService;

    protected $description = 'Fetches categories provided by the google my business api 
                              and updates the location categories in our database accordingly';
    protected $signature = 'import:googleCategories';

    protected $company;
    protected $fetchedGoogleCategories;
    protected $activeDbCategories;
    protected $deletedDbCategories;

    public function handle()
    {
        DB::beginTransaction();
        try {
            $this->fetchedGoogleCategories = $this->fetchGoogleCategories();
            $this->fetchedGoogleCategories = $this->parseFetchedCategories();

            $this->activeDbCategories = $this->getActiveCategoriesInDatabase();
            $this->deletedDbCategories = $this->getDeletedCategoriesInDatabase();

            $this->compareFetchedWithActive();
            $this->restoreDeletedInDatabase();
            $this->createOrUpdateInDatabase();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            captureException($exception);
        }
    }

    private function fetchGoogleCategories(): Collection
    {
        $localHeroCompanyId = 26;
        $this->company = Company::query()->find($localHeroCompanyId);
        $this->init_google_service_mybusiness();
        return collect($this->googleMyBusinessService
            ->categories
            ->listCategories(['regionCode' => 'DE', 'languageCode' => 'de-DE'])
            ->categories);
    }

    private function parseFetchedCategories(): array
    {
        return $this->fetchedGoogleCategories->map(function ($item) {
            $category['gcid'] = $item->categoryId;
            $category['name'] = $item->displayName;
            return $category;
        })->toArray();
    }

    private function getActiveCategoriesInDatabase()
    {
        return GoogleBusinessCategory::query()->get(['gcid', 'name'])->values()->toArray();
    }

    private function getDeletedCategoriesInDatabase()
    {
        return GoogleBusinessCategory::onlyTrashed()->get(['gcid', 'name'])->values()->toArray();
    }

    private function compareFetchedWithActive()
    {
        foreach ($this->activeDbCategories as $category) {
            $isInFetchedCategories = in_array($category, $this->fetchedGoogleCategories);
            if ($isInFetchedCategories) {
                $this->removeFromFetchedCategories($category);
            } else {
                $this->deleteFromDatabase($category);
            }
        }
    }

    private function removeFromFetchedCategories(array $category)
    {
        $position = array_search($category, $this->fetchedGoogleCategories);
        unset($this->fetchedGoogleCategories[$position]);
    }

    private function deleteFromDatabase(array $category)
    {
        $googleBusinessCategory = GoogleBusinessCategory::whereGcid($category['gcid'])->first();
        $locationsUsingCategory = $googleBusinessCategory->mainTypeLocations()->get();
        foreach ($locationsUsingCategory as $location) {
            $this->markLocationProblematic($location);
        }
        $googleBusinessCategory->locations()->detach();
        $googleBusinessCategory->delete();
    }

    private function markLocationProblematic($location)
    {
        $taskTemplate = 'lli.location.problemMailClickUpTemplateGoogleAPI';
        $useCase = new TagProblemLocationUseCase($location, $taskTemplate);
        $useCase->tagLocationAsProblematic();
    }

    private function restoreDeletedInDatabase()
    {
        foreach ($this->deletedDbCategories as $category) {
            $position = array_search($category, $this->fetchedGoogleCategories);
            if ($position !== false) {
                $fetchedCategory = $this->fetchedGoogleCategories[$position];
                $googleCategory = GoogleBusinessCategory::whereGcid($category->gcid)->first();
                $googleCategory->restore();
                $googleCategory->name = $fetchedCategory['name'];
                $googleCategory->save();
                $this->removeFromFetchedCategories($category);
            }
        }
    }

    private function createOrUpdateInDatabase()
    {
        foreach ($this->fetchedGoogleCategories as $category) {
            $googleBusinessCategory = GoogleBusinessCategory::whereGcid($category['gcid'])->first();
            if ($googleBusinessCategory) {
                $googleBusinessCategory->name = $category['name'];
                $googleBusinessCategory->save();
            } else {
                GoogleBusinessCategory::create($category);
            }
        }
    }


}
