<?php

namespace LocalheroPortal\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LocalheroPortal\Callcenter\Http\Resources\LeadSingleResource;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

// TODO: rename
class UserLeadController extends Controller
{

    public function __invoke(Request $request, User $user = null)
    {
        $pagination = $request->per_page ?? 100;
        $sortBy = $request->sort_by ?? false;
        $leads = $user->leads();
        if ($request->show_past === "false") {
            $leads->where('closed_until', '>', Carbon::now('Europe/Berlin'));
        }
        if ($request->filter) {
            $leads->where('status', '=', (int) $request->filter);
        }
        if ($request->expert_status) {
            $leads->where('expert_status', '=', $request->expert_status);
        }
        if ($sortBy) {
            $leads->orderBy($sortBy);
        }
        return LeadSingleResource::collection($leads->paginate($pagination));
    }
}
