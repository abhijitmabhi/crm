<?php
namespace LocalheroPortal\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LocalheroPortal\Models\Lead;

class FullcalendarEventResource extends JsonResource
{
    public function toArray($request)
    {
        $eventData = array_merge(
            [
            'id'    => $this->id,
            'title' => $this->genEventText(),
            'start' => $this->event_begin->toIso8601String(),
            'end'   => $this->event_end->toIso8601String(),
            'body'  => $this->body,
            'isDeleted' => !!$this->deleted_at,
        ],
            $this->getColors()
        );
        foreach ($this->invitees as $invitee) {
            $eventData['invitees'][] = [
                'id'   => $invitee->id,
                'name' => $invitee->name,
            ];
        }
        foreach ($this->attendees as $attendee) {
            $eventData['attendees'][] = [
                'id'   => $attendee->id,
                'name' => $attendee->name,
            ];
        }
        if ($this->pivot->pivotParent instanceof Lead) {
            $eventData['leadId'] = $this->lead->id;
        }
        if ($owner = $this->users()->withPivotValue('role', 'owner')->first()) {
            $eventData['owner'] = [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
            ];
        }
        if ($this->pivot->role == "invitee") {
            $eventData['invitation'] = $this->id;
        }
        return $eventData;
    }

    protected function genEventText()
    {
        if ($this->pivot->role == 'lead') {
            $lead = $this->lead;
            $text = $lead->company_name . PHP_EOL;
            $text .= $this->event_begin->format('H:i') . ' - ' . $this->event_end->format('H:i') . 'Uhr' . PHP_EOL;
            $text .= $lead->city . ' - ' . $lead->zip . PHP_EOL;
            return $text;
        }
        if ($this->pivot->role == 'invitee') {
            return "Termineinladung:" . PHP_EOL . $this->body;
        }
        return $this->body;
    }

    //TODO: refactor so that colors are defined in frontend
    protected function getColors()
    {
        if ($this->deleted_at) {
            return [
                'textColor' => '#fff',
                'backgroundColor' => '#cdcdcd',
                'borderColor' => '#cdcdcd'
            ];
        }
        switch ($this->type) {
        case 'callcenter-appointment':
            return [
                'textColor' => '#fff',
                'backgroundColor' => '#db0630',
                'borderColor' => '#db0630'
            ];
        case 'general':
        case 'seminar':
        case 'networking':
            return [
                'textColor' => '#fff',
                'backgroundColor' => '#3f888f',
                'borderColor' => '#3f888f'
            ];
        case 'private':
            return [
                'textColor' => '#fff',
                'borderColor' => in_array($this->pivot->role, ['owner', 'attendee'])
                ? '#4682B4'
                : '#505050', 
                'backgroundColor' => in_array($this->pivot->role, ['owner', 'attendee'])
                    ? '#4682B4'
                    : '#505050', 
            ];
        default:
            return $this->getColorsByRole();
        }
    }

    //TODO: refactor so that colors are defined in frontend
    public function getColorsByRole()
    {
        switch ($this->pivot->role) {
            case "owner":
                return [
                    'textColor' => '#fff',
                    'backgroundColor' => '#458D84'
                ];
            case "initee":
                return [
                    'textColor' => '#fff',
                    'backgroundColor' => '#505050'
                ];
            case "atendee":
                return [
                    'textColor' => '#fff',
                    'backgroundColor' => '#4682B4'
                ];
            case "lead":
                return [
                    'textColor' => '#fff',
                    'backgroundColor' => '#db0630'
                ];
            default:
                return [
                    'textColor' => '#000',
                    'backgroundColor' => '#fff'
                ];
        }
    }
}
