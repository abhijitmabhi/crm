<?php

namespace LocalheroPortal\Core\Feature\Migration;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Callcenter\UseCase\LeadCallNotReachedUseCase;

class MigrateNotReachedTimeoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $minId;
    private $maxId;

    public function __construct(int $minId, int $maxId)
    {
        $this->minId = $minId;
        $this->maxId = $maxId;
    }

    public function handle()
    {
        $leads = Lead::withTrashed()->where('id', '>', $this->minId)
            ->where('id', '<=', $this->maxId)->get();
        foreach ($leads as $lead) {
            $comments = $lead->getCommentsNotReached()->getResults();
            $lead->not_reached_counter = count($comments);
            if ($lead->status == LeadState::NOT_REACHED) {
                $notReachedUseCase = new LeadCallNotReachedUseCase();
                $notReachedUseCase->setNotReachedTimeout($lead);
            }
            if ($lead->not_reached_counter >= 7 && in_array($lead->status, [LeadState::NOT_REACHED, LeadState::RECALL])) {
                $lead->status = LeadState::TOO_MANY_TRIES;
            }
            $lead->save();
        }
    }
}