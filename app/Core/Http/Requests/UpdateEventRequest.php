<?php

namespace LocalheroPortal\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_begin' => 'date',
            'event_end'   => 'date|after:event_begin',
            'body'        => 'between:15,2000|string',
            'invitees'    => 'array|nullable',
        ];
    }
}
