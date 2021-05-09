<?php


namespace LocalheroPortal\Callcenter\UseCase;


use LocalheroPortal\Models\LeadState;

class LeadCallNotReachedUseCase
{

    public function onNotReached($lead)
    {
        $lead->not_reached_counter++;
        $this->setNotReachedTimeout($lead);
        if ($lead->not_reached_counter >= 7) {
            $lead->status = LeadState::TOO_MANY_TRIES;
        }
    }

    public function setNotReachedTimeout($lead)
    {
        $comments = $lead->getCommentsNotReached()->getResults();
        $first_try_timestamp = $this->getCommentCreated($comments, 0);
        $next_try_timestamp = now('Europe/Berlin');

        $next_try = $lead->not_reached_counter + 1;

        switch ($next_try) {
            case 2:
            case 6:
                $next_try_timestamp = $next_try_timestamp->addHours(4);
                break;
            case 3:
                $next_try_timestamp = $next_try_timestamp->addHours(22);
                break;
            case 4:
                $next_try_timestamp = $next_try_timestamp->addHours(20);
                break;
            case 5:
                $next_try_timestamp = $next_try_timestamp->addDays(7);
                $next_try_timestamp->hours($first_try_timestamp->hour);
                $next_try_timestamp->minutes($first_try_timestamp->minute);
                break;
            default:
                $next_try_timestamp =  $next_try_timestamp->addDays(14);
                $next_try_timestamp->hours($first_try_timestamp->hour);
                $next_try_timestamp->minutes($first_try_timestamp->minute);
                break;
        }

        $lead->closed_until = $next_try_timestamp;
    }

    private function getCommentCreated($comments, $index)
    {
        //TODO: write test an maybe make extension for array class?
        return isset($comments[$index]) ? $comments[$index]->created_at : now('Europe/Berlin');
    }
}