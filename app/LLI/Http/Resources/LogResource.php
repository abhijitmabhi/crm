<?php

namespace LocalheroPortal\LLI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event' => $this->event,
            'message' => $this->message,
            'url' => Storage::disk('companyLogs')->url($this->filename),
            'download' => Storage::disk('companyLogs')->download($this->filename)
        ];
    }
}
