<?php

namespace LocalheroPortal\LLI\Feature\KeywordScraping;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use OutOfRangeException;

class KeywordScrapingParser
{

    const ARRAY_LENGTH_FOR_EACH_TERM = 3;

    protected string $unparsedSearchTerms;

    public function __construct(string $unparsedSearchTerms)
    {
        $this->unparsedSearchTerms = $unparsedSearchTerms;
    }

    public function getJsonParsedTerms(): string
    {
        $incoherentTermArray = $this->divideByNewLine();
        $removedCarriageReturnAndLessThan = $this->createProperFormat($incoherentTermArray);
        $chunkedTerms = $this->chunkEachTermInArray($removedCarriageReturnAndLessThan);
        $termsWithKeys = $this->addKeys(collect($chunkedTerms));
        return json_encode($termsWithKeys);
    }

    private function divideByNewLine(): array
    {
        return explode("\n", $this->unparsedSearchTerms);
    }

    private function createProperFormat(array $terms): array
    {
        foreach ($terms as $key => $item) {
            $terms[$key] = str_replace(["\r", "< "], '', $item);
        }

        return $terms;
    }

    private function chunkEachTermInArray(array $incoherentTerms): array
    {
        $coherentTerms = array_chunk($incoherentTerms, self::ARRAY_LENGTH_FOR_EACH_TERM);
        if ($this->isTermNotPrecedingAndSucceedingWithInt($coherentTerms)) {
            throw new InvalidArgumentException('The terms you provided are in a wrong format! They should start and end with a number. Try to copy them again.');
        }

        return $coherentTerms;
    }

    private function isTermNotPrecedingAndSucceedingWithInt(array $chunkedTerms): bool
    {
        $isWrongFormat = false;
        $ranking = 0;
        $usersFoundThroughTerm = 2;

        foreach ($chunkedTerms as $termArray) {
            if (!isset($termArray[$usersFoundThroughTerm])) {
                throw new OutOfRangeException('Ooops! Something went wrong. Make sure that you do copy each row properly.');
            }
            $isIntNotPrecedingAndSucceeding = !is_numeric($termArray[$ranking]) || !is_numeric($termArray[$usersFoundThroughTerm]);
            if ($isIntNotPrecedingAndSucceeding) {
                $isWrongFormat = true;
            }
        }
        return $isWrongFormat;
    }

    private function addKeys(Collection $termsNoKeys): Collection
    {
        $termsWithKeys = $termsNoKeys->map(function ($termArray) {
            return collect($termArray)->keyBy(function ($item, $key) {
                if ($key == '0') {
                    return 'ranking';
                } else {
                    if ($key == '1') {
                        return 'searchTerm';
                    } else {
                        return 'usersFoundThroughTerm';
                    }
                }
            });
        });
        return $termsWithKeys;
    }

}