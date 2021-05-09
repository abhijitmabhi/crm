<?php

namespace LocalheroPortal\Core\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Events\CalendarUpdated;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Http\Requests\AttendEventRequest;
use LocalheroPortal\Core\Http\Requests\CreateEventRequest;
use LocalheroPortal\Core\Http\Requests\UpdateEventRequest;
use LocalheroPortal\Core\Http\Resources\FullcalendarEventResource;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\CalendarEventType;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\User;

class UserCalendarController extends Controller
{
    /**
     * @param User               $user
     * @param AttendEventRequest $request
     */
    public function attend(User $user, CalendarEvent $appointment, AttendEventRequest $request)
    {
        $user->attendEvent($appointment);
        event(new CalendarUpdated((int) $user->id));
        return Response::json(['message' => 'event updated']);
    }

    /**
     * @param User $user
     */
    public function index(Request $request, User $user)
    {
        $startTime = $request['startTime'];
        $endTime = $request['endTime'];
        $appointments = $user->calendarEvents()->whereDate('event_begin', '>=', $startTime)->whereDate('event_end', '<', $endTime);
        if (Gate::allows('supervise-callcenter')) {
            $appointments->withTrashed();
        }

        $appointments = $appointments->get();
        $leads = $user->leads()->whereHas('calendarEvents', function ($query) use ($endTime, $startTime) {
            if (Gate::allows('supervise-callcenter')) {
                $query = $query->withTrashed();
            }
            return $query->whereDate('event_begin', '>=', $startTime)->whereDate('event_end', '<', $endTime);
        })->with('calendarEvents')->cursor();
        foreach ($leads as $lead) {
            $events = $lead->calendarEvents()->whereDate('event_begin', '>=', $startTime)->whereDate('event_end', '<', $endTime)->withPivot('role');
            if (Gate::allows('supervise-callcenter')) {
                $events->withTrashed();
            }
            $events->get()->reduce(function ($appointments, $appointment) use ($lead) {
                $appointment->lead = $lead;
                $appointments->push($appointment);
                return $appointments;
            }, $appointments);
        }
        return FullcalendarEventResource::collection($appointments);
    }

    /**
     * @param User               $user
     * @param CreateEventRequest $request
     */
    public function store(User $user, CreateEventRequest $request)
    {
        $appointment = new CalendarEvent($request->getEventArray());
        foreach ($request->input('invitees') as $invitee) {
            $invitedUser = User::find($invitee);
            $invitedUser->invitedToEvents()->save($appointment);
            event(new CalendarUpdated((int) $invitee));
        }
        $user->ownedEvents()->save($appointment);
        event(new CalendarUpdated((int) $user->id));

        return Response::json(['message' => 'event saved']);
    }

    public function update(User $user, CalendarEvent $appointment, UpdateEventRequest $request)
    {
        if (isset($request->event_begin) || isset($request->event_end) || isset($request->body)) {
            if (isset($request->event_begin)) {
                $appointment->event_begin = Carbon::parse($request->input('event_begin'))->addHour();
            }
            if (isset($appointment->event_end)) {
                $appointment->event_end = Carbon::parse($request->input('event_end'))->addHour();
            }
            if (isset($request->body)) {
                $appointment->body = $request->body;
            }
            $appointment->save();
        }
        event(new CalendarUpdated((int) $user->id));
        foreach ($request->input('invitees') as $inviteeId) {
            $invitedUser = User::find($inviteeId)->first();
            if (!$invitedUser->invitedToEvents()->get()->contains($appointment)) {
                $invitedUser->invitedToEvents()->attach($appointment);
            }
            event(new CalendarUpdated((int) $user->id));
        }
        return Response::json(['message' => 'event updated']);
    }

    public function restore(Request $request, User $user, string $eventId)
    {
        $event = CalendarEvent::withTrashed()->findOrFail($eventId);
        $event->restore();
        event(new CalendarUpdated((int) $user->id));
        return Response::json(['message' => 'event restored']);
    }

    public function destroy(User $user, CalendarEvent $appointment, Request $request)
    {
        $appointment->delete();
        return Response::json(['message' => 'event deleted']);
    }
}
