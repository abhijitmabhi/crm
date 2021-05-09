<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Admin;

use Carbon\Carbon;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\CommentRepository;
use Illuminate\Http\Request;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class AgentStatsController extends Controller
{

    public function getAgentStats()
    {
        $startDate = Carbon::now('Europe/Berlin')->startOfWeek();
        $endDate = Carbon::now('Europe/Berlin')->endOfWeek();
        return $this->handleAgentStatsRequest($startDate, $endDate);
    }

    public function getAgentStatsWithDates(Request $request)
    {
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);

        return $this->handleAgentStatsRequest($startDate, $endDate);
    }

    private function handleAgentStatsRequest(Carbon $startDate, Carbon $endDate)
    {
        $commentRepo = new CommentRepository();
        $statistics = $commentRepo->getCountByCallReasonAndUser($startDate, $endDate);
        $agentStatistics = $this->statsForAgents($statistics);

        $calendarEvents = CalendarEvent::withTrashed()
            ->where('event_begin', '>=', $startDate)
            ->where('event_begin', '<=', $endDate)
            ->where('type', 'callcenter-appointment')
            ->whereRaw('WEEKDAY(event_begin) != 6')
            ->get();
        $firstAppointmentCount = 0;
        $appointmentsWithLeads = 0;
        $movedAppointments = 0;
        $appointmentsWithoutLeads = 0;
        foreach ($calendarEvents as $calendarEvent) {
            $lead = $calendarEvent->leads()->withTrashed()->first();
            if ($lead) {
                $appointmentsWithLeads += 1;
                $firstLeadAppointment = $lead->calendarEvents()->withTrashed()->first();
                if ($firstLeadAppointment->id == $calendarEvent->id) {
                    $firstAppointmentCount += 1;
                } elseif ($calendarEvent->deleted_at) {
                    $wasMoved = $lead->calendarEvents()->withTrashed()
                        ->where('created_at', '>', $calendarEvent->created_at)
                        ->where('created_at', '<', $calendarEvent->event_begin)->exists();
                    $movedAppointments += $wasMoved ? 1 : 0;
                }
            } else {
                $appointmentsWithoutLeads += 1;
            }
        }

        return view('callcenter.AgentStatsView', [
            'agentStatistics' => $agentStatistics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'firstAppointmentCount' => $firstAppointmentCount,
            'appointmentsWithLeads' => $appointmentsWithLeads,
            'movedAppointments' => $movedAppointments,
            'appointmentsWithoutLeads' => $appointmentsWithoutLeads
        ]);
    }

    public function statsForAgents($statistics)
    {
        $agents = User::where('last_name', '!=', '')->orderBy('last_name')->get();
        $agents = $agents->filter(fn($agent) => $agent->hasRole(RoleType::CALL_CENTER_AGENT));
        $agentStatistics = array();

        foreach ($agents as $agent) {
            $name = $agent->last_name.', '.$agent->first_name;
            $agentStatistic = $statistics->where('user_id', $agent->id)->first();
            if ($agentStatistic) {
                $agentStatistic->name = $name;
                array_push($agentStatistics, $agentStatistic);
            }
        }
        return $agentStatistics;
    }

}
