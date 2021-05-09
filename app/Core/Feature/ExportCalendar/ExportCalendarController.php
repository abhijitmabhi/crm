<?php

namespace LocalheroPortal\Core\Feature\ExportCalendar;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Property\Event\Organizer;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\App;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class ExportCalendarController extends Controller
{

    public function __invoke(User $expert)
    {
        if (App::environment('local')) {
            app('debugbar')->disable();
        }

        $calendar = new Calendar(config('app.name'));
        $organizer = new Organizer('MAILTO:service@localhero.de');

        $leads = $expert->leads()->has('calendarEvents')->with('calendarEvents')->cursor();

        foreach ($leads as $lead) {
            foreach ($lead->calendarEvents()->get() as $appointment) {
                $event = new Event();
                $event->setDtStart($appointment->event_begin->toDateTime());
                $event->setDtEnd($appointment->event_end->toDateTime());
                $event->setOrganizer($organizer);
                foreach ($appointment->attendees as $attendee) {
                    $event->addAttendee($attendee->name);
                }
                $event->setSummary($lead->company_name);
                $event->setDescription($appointment->body);
                $calendar->addComponent($event);
            }
        }

        $otherAppointments = $expert->calendarEvents()->cursor();
        foreach ($otherAppointments as $appointment) {
            $event = new Event();
            $event->setDtStart($appointment->event_begin->toDateTime());
            $event->setDtEnd($appointment->event_end->toDateTime());
            $event->setOrganizer($organizer);
            foreach ($appointment->attendees as $attendee) {
                $event->addAttendee($attendee->name);
            }
            $event->setSummary($appointment->body);
            $event->setDescription($appointment->body);
            $calendar->addComponent($event);
        }

        $response = new IlluminateResponse();
        $response->setContent($calendar->render());
        $response->header('Content-Type', 'text/calendar; charset=utf-8');
        $response->header('Content-Disposition', 'attachment; filename="cal.ics');
        $response->sendHeaders();
        return $response;
    }
}
