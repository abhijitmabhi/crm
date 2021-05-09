<?php

namespace Tests\Integration\Callcenter\UseCase;

use LocalheroPortal\Callcenter\UseCase\LeadCallNotReachedUseCase;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use Tests\Integration\IntegrationTestCase;

class NotReachedUseCaseTest extends IntegrationTestCase
{

    private $useCase;
    private $lead;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = new LeadCallNotReachedUseCase();
        $this->lead = new Lead();
        //TODO: make test work with comments usage
//        $this->lead->comments->add(new Comment([
//            'reason'           => 'NOT_REACHED',
//            'body'             => '',
//            'user_id'          => 0,
//            'commentable_type' => 'lead',
//            'commentable_id'   => 0,
//            'date'             => $this->getFirstCommentCreated()
//        ]));
    }

    public function getFirstCommentCreated()
    {
        $firstCommentCreated = now('Europe/Berlin');
//        $firstCommentCreated->setTime(10, 0);
        return $firstCommentCreated;
    }

    public function testSetNotReachedTimeout()
    {
        $this->validateSetNotReachedTimeout(1, now('Europe/Berlin')->addHours(4));
        $this->validateSetNotReachedTimeout(2, now('Europe/Berlin')->addHours(22));
        $this->validateSetNotReachedTimeout(3, now('Europe/Berlin')->addHours(20));
        $this->validateSetNotReachedTimeout(4, $this->getFirstCommentCreated()->addDays(7));
        $this->validateSetNotReachedTimeout(5, now('Europe/Berlin')->addHours(4));
        $this->validateSetNotReachedTimeout(6, $this->getFirstCommentCreated()->addDays(14));
    }

    private function validateSetNotReachedTimeout($counter, $expected)
    {
        $this->lead->not_reached_counter = $counter;
        $this->useCase->setNotReachedTimeout($this->lead);
        self::assertEquals(
            $expected->toDateTimeString(),
            $this->lead->closed_until->toDateTimeString()
        );
    }

    public function testOnNotReached()
    {
        $this->lead->not_reached_counter = 0;
        $this->useCase->onNotReached($this->lead);
        self::assertEquals(1, $this->lead->not_reached_counter);

        $this->lead->not_reached_counter = 7;
        $this->useCase->onNotReached($this->lead);
        self::assertEquals(LeadState::TOO_MANY_TRIES, $this->lead->status);
    }
}
