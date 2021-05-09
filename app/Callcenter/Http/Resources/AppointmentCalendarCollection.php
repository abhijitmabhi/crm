<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use LocalheroPortal\Models\LeadExpertAcceptance;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\CalendarEvent;

class AppointmentCalendarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [];
        foreach ($this->collection as $lead) {
            if ($lead['expert_status'] === LeadExpertAcceptance::REJECTED) {
                continue;
            }
            foreach ($lead->calendarEvents as $event) {

                $response[] = [
                    'leadId'      => $lead['id'],
                    'title'       => $this->genEventText($lead, $event),
                    'start'       => $event->event_begin->toIso8601String(),
                    'end'         => $event->event_end->toIso8601String(),
                    'description' => $event->body,
                    'acceptance'  => $lead['expert_status'],
                    'city'        => $lead['city'],
                ];
            }

        }
        return $response;
    }

    /**
     * @return mixed
     */
    private function genEventText(Lead $lead, CalendarEvent $event)
    {
        $title = $lead->company_name . PHP_EOL;
        $title .= $event->event_begin->format('H:i') . ' - ' . $event->event_end->format('H:i') . 'Uhr' . PHP_EOL;
        $title .= $lead->city . ' - ' . $lead->zip . PHP_EOL;
        return $title;
    }
}