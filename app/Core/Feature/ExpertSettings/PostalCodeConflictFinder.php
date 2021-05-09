<?php


namespace LocalheroPortal\Core\Feature\ExpertSettings;


use Illuminate\Support\Collection;

class PostalCodeConflictFinder
{

    protected Collection $allCodes;

    public function __construct(Collection $allCodes)
    {
        $this->allCodes = $allCodes;
    }

    public function getConflicts(Collection $codes): Collection
    {
        $regexCodes = $this->getRegexCodes($codes);
        $exactCodes = $this->getExactCodes($codes);
        $conflicts = $this->getExactConflicts($exactCodes);
        $conflicts = $conflicts->merge($this->getRegexConflicts($regexCodes));
        return $conflicts;
    }

    private function getRegexCodes(Collection $codes): Collection
    {
        return $codes->filter(fn($code) => str_contains($code, '*'));
    }

    private function getExactCodes(Collection $codes): Collection
    {
        return $codes->filter(fn($code) => !str_contains($code, '*'));
    }

    private function getExactConflicts(Collection $exactCodes): Collection
    {
        return $exactCodes
            ->map(function ($exactCode) {
                $conflicts = $this->allCodes->filter(fn($code) => $code == $exactCode);
                $conflicts = $conflicts->merge($this->isIncludedInRegex($exactCode));
                return $conflicts;
            })
            ->flatten();
    }

    private function getRegexConflicts(Collection $regexCodes): Collection
    {
        return $regexCodes
            ->map(function ($regexCode) {
                $regexCode = substr($regexCode, 0, -1);
                $conflicts = $this->allCodes->filter(fn($code, $index) => str_starts_with($code, $regexCode));
                $conflicts = $conflicts->merge($this->isIncludedInRegex($regexCode));
                return $conflicts;
            })
            ->flatten();
    }

    private function isIncludedInRegex(string $codePart): Collection
    {
        $conflicts = collect();
        while ($conflicts->isEmpty() && $codePart) {
            $codePart = substr($codePart, 0, -1);
            $conflicts = collect($this->allCodes->first(fn($code, $index) => $code == $codePart.'*'));
        }
        return $conflicts;
    }

}