<?php

namespace Tests\Integration\LLI\Feature\Scraping;


use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use LocalheroPortal\Models\LLI\KeywordUsageResult;
use Tests\Integration\IntegrationTestCase;
use Tests\ModelDummyTraits\TestingLocationTrait;
use Tests\ModelDummyTraits\TestingUserTrait;

class KeywordScrapingControllerTest extends IntegrationTestCase
{

    use WithoutMiddleware;
    use TestingUserTrait;
    use TestingLocationTrait;

    private string $unparsedSearchTerms = "1\nTESTONLY\n19.191\n\r2\nTEST ONLY\n2.315\n3\nTEST TEST\n1.006\n4\nTEST\n< 15";

    private array $resultParserFunction =   [
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

    private $location;
    private $locationId;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->location = $this->createTestLocation();
        $this->locationId = $this->location->id;
        $this->user = $this->createTestUser();
    }


    public function testControllerProperInput()
    {
        $request = Request::create('/api/location/' . $this->locationId . '/scraping-results', 'POST', ['unparsedSearchTerms' => $this->unparsedSearchTerms]);
        $this->actingAs($this->user);
        app()->handle($request);

        self::assertDatabaseHas('keyword_usage_results', [
            'results' => collect($this->resultParserFunction)->toJson()
        ]);

        KeywordUsageResult::where('results', collect($this->resultParserFunction)->toJson())->delete();

        self::assertDatabaseMissing('keyword_usage_results', [
            'results' => collect($this->resultParserFunction)->toJson()
        ]);
    }


    protected function tearDown() : void
    {
        $this->deleteTestUser($this->user);
        $this->deleteTestLocation($this->location);
        parent::tearDown();
    }

}
