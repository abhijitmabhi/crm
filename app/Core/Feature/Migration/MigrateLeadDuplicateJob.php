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
use LocalheroPortal\Core\Repository\LeadRepository;

class MigrateLeadDuplicateJob implements ShouldQueue
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
        $repo = new LeadRepository();
        $leads = Lead::query()->where('id', '>', $this->minId)
            ->where('id', '<=', $this->maxId)->get();
        $deletableStatuses = [LeadState::NO_INTEREST, LeadState::NOT_REACHED, LeadState::OPEN, LeadState::RECALL, LeadState::TOO_MANY_TRIES, LeadState::INVALID];
        foreach ($leads as $lead) {
            $similarLeads = $repo->getAllSimilarLeads($lead->email, $lead->website, $lead->phone1, $lead->phone2);
            if (count($similarLeads) > 1) {
                $deletableLeads = $similarLeads->filter(function ($similarLead) use ($deletableStatuses) {
                    return in_array($similarLead->status, $deletableStatuses);
                });
                if (count($deletableLeads) == count($similarLeads)) {
                    $deletableLeads = $deletableLeads->reverse();
                    $deletableLeads->pop();
                }
                foreach ($deletableLeads as $deletableLead) {
                    $deletableLead->delete();
                }
            }
        }
        return response()->json('Done.');
    }
}