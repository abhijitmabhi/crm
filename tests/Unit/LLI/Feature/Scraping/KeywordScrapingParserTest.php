<?php

namespace Tests\Unit\LLI\Feature\Scraping;


use Illuminate\Foundation\Testing\WithoutMiddleware;
use LocalheroPortal\LLI\Feature\KeywordScraping\KeywordScrapingParser;
use OutOfRangeException;
use Tests\Unit\UnitTestCase;

class KeywordScrapingParserTest extends UnitTestCase
{

    use WithoutMiddleware;

    private string $unparsedSearchTerms = "1\nTESTONLY\n19.191\n2\nTEST ONLY\n2.315\n3\nTEST TEST\n1.006\n4\nTEST\n15";
    private array $properResultParserFunction =   [
        [
            'ranking' => "1",
            'searchTerm' => "TESTONLY",
            "usersFoundThroughTerm" => "19.191"
        ],
        [
            'ranking' => "2",
            'searchTerm' => "TEST ONLY",
            "usersFoundThroughTerm" => "2.315"
        ],
        [
            'ranking' => "3",
            'searchTerm' => "TEST TEST",
            "usersFoundThroughTerm" => "1.006"
        ],
        [
            'ranking' => "4",
            'searchTerm' => "TEST",
            "usersFoundThroughTerm" => "15"
        ],
    ];

    private string $unparsedSearchTermsWithCarriageReturn = "1\n\rTESTONLY\n\r19.191\n\r2\n\rTEST ONLY\n\r2.315";
    private array $resultParserFunctionWithCarriageReturn = [
        [
            'ranking' => "1",
            'searchTerm' => "TESTONLY",
            "usersFoundThroughTerm" => "19.191"
        ],
        [
            'ranking' => "2",
            'searchTerm' => "TEST ONLY",
            "usersFoundThroughTerm" => "2.315"
        ]
    ];

    private string $unparsedSearchTermsWithLessThanOperator = "1\n\rTESTONLY\n\r< 15";
    private array $resultParserFunctionWithLessThanOperator = [
        [
            'ranking' => "1",
            'searchTerm' => "TESTONLY",
            "usersFoundThroughTerm" => "15"
        ]
    ];


    private string $inputTooShort = "1	1";
    private string $inputNoNumericValue = "adidas berlin";
    private string $missingUsersFoundThroughTermValue = "1\nTESTONLY\n19.191\n2\nTEST ONLY\n2.315\n3\nTEST TEST\n1.006\n4\nTEST";

    public function testParserProperInput() {
        $test = new KeywordScrapingParser($this->unparsedSearchTerms);
        $parsedTerms = $test->getJsonParsedTerms();

        self::assertEquals(collect($this->properResultParserFunction)->toJson(), $parsedTerms);
    }

    public function testParserWithCarriageReturn()
    {
        $test = new KeywordScrapingParser($this->unparsedSearchTermsWithCarriageReturn);
        $parsedTerms = $test->getJsonParsedTerms();

        self::assertEquals(collect($this->resultParserFunctionWithCarriageReturn)->toJson(), $parsedTerms);
    }

    public function testParserWithLessThanOperator()
    {
        $test = new KeywordScrapingParser($this->unparsedSearchTermsWithLessThanOperator);
        $parsedTerms = $test->getJsonParsedTerms();

        self::assertEquals(collect($this->resultParserFunctionWithLessThanOperator)->toJson(), $parsedTerms);
    }

    public function testParserMissingUsersFoundThroughTermValue()
    {
        self::expectException(OutOfRangeException::class);
        self::expectExceptionMessage("Ooops! Something went wrong. Make sure that you do copy each row properly.");
        $testTest = new KeywordScrapingParser($this->missingUsersFoundThroughTermValue);
        $testTest->getJsonParsedTerms();
    }

    public function testParserNoNumericValue()
    {
        self::expectException(OutOfRangeException::class);
        self::expectExceptionMessage("Ooops! Something went wrong. Make sure that you do copy each row properly.");

        $testTest = new KeywordScrapingParser($this->inputNoNumericValue);
        $testTest->getJsonParsedTerms();

    }

}
