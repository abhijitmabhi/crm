<?php


namespace Tests\Unit\Core\Feature\ExpertSettings;

use Illuminate\Support\Collection;
use LocalheroPortal\Core\Feature\ExpertSettings\PostalCodeConflictFinder;
use Tests\Unit\UnitTestCase;

class PostalCodeConflictFinderTest extends UnitTestCase
{

    public function testNoConflicts()
    {
        $allCodes = new Collection();
        $testCodes = new Collection();
        $finder = new PostalCodeConflictFinder($allCodes);
        self::assertEmpty($finder->getConflicts($testCodes));
        $testCodes->add('762*');
        self::assertEmpty($finder->getConflicts($testCodes));
        $testCodes->add('76133');
        self::assertEmpty($finder->getConflicts($testCodes));
        $allCodes->add('80000');
        self::assertEmpty($finder->getConflicts($testCodes));
        $allCodes->add('81*');
        self::assertEmpty($finder->getConflicts($testCodes));
    }

    public function testExactConflicts()
    {
        $allCodes = new Collection(['77133', '762*']);
        $finder = new PostalCodeConflictFinder($allCodes);
        self::assertContains('77133', $finder->getConflicts(collect(['77133'])));
        self::assertContains('762*', $finder->getConflicts(collect(['76233'])));
    }

    public function testRegexConflicts()
    {
        $allCodes = new Collection(['77133', '762*']);
        $finder = new PostalCodeConflictFinder($allCodes);
        self::assertContains('762*', $finder->getConflicts(collect(['762*'])));
        self::assertContains('762*', $finder->getConflicts(collect(['7621*'])));
        self::assertContains('762*', $finder->getConflicts(collect(['76*'])));
        self::assertContains('77133', $finder->getConflicts(collect(['77*'])));
    }
}