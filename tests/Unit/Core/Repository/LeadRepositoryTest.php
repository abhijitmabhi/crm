<?php

namespace Tests\Unit\Core\Repository;

use LocalheroPortal\Core\Repository\LeadRepository;
use Tests\Unit\UnitTestCase;

class LeadRepositoryTest extends UnitTestCase
{

    private $repo;

    public function setUp(): void
    {
        parent::setUp();
        $this->repo = new LeadRepository();
    }

    public function testGetCategoryBlacklist()
    {
        $time = now('Europe/Berlin');
        $time->hour = 9;
        $blacklist = $this->repo->getCategoryBlackList($time);
        $comparison = [
            'Autohaus',
            'Optiker',
            'Hörgeräte',
            'Küchenstudios',
            'Möbelhaus',
            'Brautmodegeschäfte',
            'Gartencenter',
            'Bettenhaus',
            'Fahrradhändler',
            'Motorradhändler'
        ];
        foreach ($comparison as $test) {
            self::assertContains($test, $blacklist, 'Blacklist is missing category.');
        }

        // set to wednesday
        $time->setDate(2020, 8, 12);
        $time->hour = 13;
        self::assertTrue($time->isDayOfWeek('wednesday'));
        $blacklist = $this->repo->getCategoryBlackList($time);
        $comparison = [
            'Schönheitschirurg',
            'Physiotherapeut',
            'Zahnarzt',
            'Orthopäde',
            'Augenarzt',
            'Arzt'
        ];
        foreach ($comparison as $test) {
            self::assertContains($test, $blacklist, 'Blacklist is missing category.');
        }
    }
}
