<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

abstract class IntegrationTestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithoutMiddleware;
}
