<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Http\Request;
use LocalheroPortal\Callcenter\Http\Resources\AppointmentCalendarCollection;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class AppointmentController extends Controller
{
    /**
     * @param  Request                         $request
     * @return AppointmentCalendarCollection
     */
    public function index(Request $request)
    {
        if ($request->lead) {
            $lead = Lead::find($request->lead);
            return $lead->calendarEvents()->orderBy('event_begin')->get();
        }
        $leads = Lead::stateAppointment();
        if ($request->start) {
            $leads->where('closed_until', '<', $request->start);
        }
        if ($request->end) {
            $leads->where('closed_until', '>', $request->end);
        }
        return new AppointmentCalendarCollection($leads->get());
    }

    /**
     * @param  int                             $id
     * @return AppointmentCalendarCollection
     */
    public function show(int $id)
    {
        $leads = Lead::forUser(User::find($id))->has('calendarEvents')->with('calendarEvents')->get();

        return new AppointmentCalendarCollection($leads);
    }
}
