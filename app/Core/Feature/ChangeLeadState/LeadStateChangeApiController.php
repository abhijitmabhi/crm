<?php

namespace LocalheroPortal\Core\Feature\ChangeLeadState;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadStateTag;
use LocalheroPortal\Models\User\RoleType;
use function Sentry\captureException;


class LeadStateChangeApiController extends Controller
{

    public function changeLeadState(Lead $lead, LeadStateChangeRequest $request)
    {
        DB::beginTransaction();
        try {
            $leadStateChangeUseCase = new LeadStateChangeUseCase($lead, $request);
            $leadStateChangeUseCase->changeLeadState();

            $isFollowUpAgent = Auth::user()->hasRole(RoleType::FOLLOW_UP_AGENT);
            if ($isFollowUpAgent) {
                $followUpCallUseCase = new FollowUpCallUseCase($lead, $request->state);
                $followUpCallUseCase->changeLeadState();
            }

            //TODO
//            if ($lead->isAcceptance && $lead->expert_status == LeadExpertAcceptance::REJECTED) {
//                $toNotify = User::byRole(RoleType::CALLCENTER_SUPERVISOR)->get();
//                $toNotify = $toNotify->merge($lead->expert->callagents->map(function ($pivot) {
//                    return $pivot->agent;
//                }));
//                Notification::send($toNotify, new AppointmentRejected(
//                    $lead->expert,
//                    $lead,
//                    $request->reasoning ?? 'Der Experte hat keine Begründung geliefert'
//                ));
//            }

            Cache::forget(Auth::id().'lead');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::json(['errors' => ['Status konnte nicht geändert werden.']], 500);
        }

        return Response::json(['message' => 'lead saved']);
    }

    public function skipFollowUp(Lead $lead) {
        DB::beginTransaction();
        try {
            $lead->addState(LeadStateTag::FOLLOW_UP_SKIPPED);
            $lead->blocked = false;
            $lead->save();
            Cache::forget(Auth::id().'lead');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::json(['errors' => ['Status konnte nicht geändert werden.']], 500);
        }

        return Response::json(['message' => 'lead saved']);
    }

}
