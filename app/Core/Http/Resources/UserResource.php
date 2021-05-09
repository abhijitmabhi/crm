<?php

namespace LocalheroPortal\Core\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Models\LeadState;

class UserResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($user) use ($request) {
            $item = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ];
            return $item;
        });
    }
}
