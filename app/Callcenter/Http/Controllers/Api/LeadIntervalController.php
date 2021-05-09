<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;

class LeadIntervalController extends Controller
{
    /**
     * @param  Lead           $lead
     * @return JsonResponse
     */
    public function destroy(Lead $lead, $id): JsonResponse
    {
        $lead->intervals->where('id', $id)->delete();
        return Response::json(['message' => 'Lead deleted']);
    }

    /**
     * @param  Lead           $lead
     * @return JsonResponse
     */
    public function index(Lead $lead): JsonResponse
    {
        return Response::json($lead->intervals->toArray());
    }

    /**
     * @param  Lead           $lead
     * @param  int            $id
     * @return JsonResponse
     */
    public function show(Lead $lead, int $id): JsonResponse
    {
        return Response::json($lead->intervals->where('id', $id));
    }

    /**
     * @param  Lead    $lead
     * @param  Request $request
     * @return void
     */
    public function store(Lead $lead, Request $request): void
    {
        $user = Auth::id();
        $lead->intervals()->attach([$user => [
            'time_spent' => $request->time_spent,
        ]]);
    }
}
