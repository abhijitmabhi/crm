<?php
namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Http\Request;
use LocalheroPortal\Callcenter\Http\Resources\ExpertLeadListResource;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Models\User\User;

class ExpertLeadController extends Controller
{
    public function index(Request $request, User $expert)
    {
        $leads = $expert->leads()->paginate($request->per_page ?? 20);
        return ExpertLeadListResource::collection($leads);
    }

    public function getExpertPipelineStats(Request $request)
    {
        $expert = User::withTrashed()->find($request->expertId);
        $leadRepo = new LeadRepository();
        $stats = $leadRepo->countPipelineByStatusForExpert($expert)[0];
        $stats->pipelineCount = $stats->openCount + $stats->invalidCount + $stats->recallCount + $stats->noInterestCount
            + $stats->notReachedCount;
        return $stats;
    }
}
