<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentSingleResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $comment = [
            'id' => $this->id,
            'body' => nl2br($this->body),
            'reason' => $this->reason,
            'user_id' => $this->user_id,
            'lead_id' => $this->lead_id,
            'created_at' => $this->created_at->format('d.m.Y H:i'),
            'time_spent' => $this->time_spent,
        ];
        $this->media->each(function ($item) use (&$comment) {
            $imageUrl = Storage::disk($item['disk'])->url('media/' . $item['order_column'] . '/' . $item['file_name']);
            $comment['images'][] = $imageUrl;
        });
        return $comment;
    }
}
