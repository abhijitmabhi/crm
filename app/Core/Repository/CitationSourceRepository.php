<?php


namespace LocalheroPortal\Core\Repository;

use Illuminate\Support\Collection;
use LocalheroPortal\Models\LLI\CitationSource;

class CitationSourceRepository {

    public function getAllCategories(): Collection
    {
        return CitationSource::query()->pluck('category')->unique()->values();
    }

}
