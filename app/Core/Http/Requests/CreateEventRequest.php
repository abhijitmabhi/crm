<?php

namespace LocalheroPortal\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

class CreateEventRequest extends FormRequest
{
    public function attributes()
    {
        return [
            'type'        => 'Terminart',
            'event_end'   => 'Termin Ende',
            'event_begin' => 'Termin Beginn',
            'body'        => 'Beschreibung',
            'invitees'    => 'Eingeladene Nutzer',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'        => 'nullable|string',
            'event_begin' => 'required|date',
            'event_end'   => 'required|date|after:event_begin',
            'body'        => 'required|between:15,2000|string',
            'invitees'    => 'array|nullable',
        ];
    }

    public function getEventArray()
    {
        return [
            'type' => $this->input('type') ?? 'general',
            'body'        => $this->input('body'),
            'event_end'   => Carbon::parse($this->input('event_end')),
            'event_begin' => Carbon::parse($this->input('event_begin')),
        ];
    }
}
