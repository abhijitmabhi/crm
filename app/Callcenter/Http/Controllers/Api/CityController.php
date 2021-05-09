<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Lead;

class CityController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Lead::query()->select('city');
        if ($request->expert) {
            $query->where('expert_id', $request->expert);
        }
        if ($request->input('status') && is_array($request->input('status'))) {
            $query->whereIn('status', $request->input('status'));
        }
        if (false !== $request->contact_name) {
            if ($request->contact_name) {
                $query->where('contact_name', $request->contact_name);
            } else {
                $query->whereNull('contact_name');
            }
        }
        if (!$request->contact_name && 'boolean' !== gettype($request->contact_name)) {
            $query->selectRaw('COUNT(id) as invalid_leads');
        }
        return $query->groupBy('city')->get();
    }
}
