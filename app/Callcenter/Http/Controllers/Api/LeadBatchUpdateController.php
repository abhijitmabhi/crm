<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use LocalheroPortal\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\Lead;

class LeadBatchUpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'agent'   => 'required_without:expert',
            'expert'  => 'required_without:agent',
            'after'   => 'date|before_or_equal:before',
            'before'  => 'date|after_or_equal:after',
            'filter'  => ['integer', function ($_, $num, $fail) {
                if (!in_array($num, LeadState::getValues())) {
                    $fail("Lead status $num is unknown");
                }
            }],
            'update'  => ['required', 'array', function ($attribute, array $toUpdate, $fail) {
                $fillables = (new Lead())->getFillable();
                $toUpdateKeys = array_keys($toUpdate);
                if (0 !== count($wrong_keys = array_diff($toUpdateKeys, $fillables))) {
                    $fail("$attribute contains illegal keys: " . implode(', ', $wrong_keys));
                }
            }],
        ]);
        $leads = Lead::query();
        if ($request->agent) {
            $leads->whereAgentId($request->agent);
        }
        if ($request->expert) {
            $leads->whereExpertId($request->expert);
        }
        if ($request->filter) {
            $leads->whereStatus($request->filter);
        }
        if ($request->before) {
            $leads->where('closed_until', '<', Carbon::parse($request->before));
        }
        if ($request->after) {
            $leads->where('closed_until', '>', Carbon::parse($request->after));
        }
        return $leads->update($request->update);
    }
}
