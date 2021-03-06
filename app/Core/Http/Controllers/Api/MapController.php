<?php

namespace LocalheroPortal\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Http\Resources\MapPinCollection;
use LocalheroPortal\Models\LLI\Location;

class MapController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return MapPinCollection
     */
    public function index(Request $request)
    {
        $leadQuery = Lead::withTrashed()->whereHas('expert')->with('expert')->where('status', '!=', LeadState::INVALID)->where('status', '!=', LeadState::CLOSED)->select(['id', 'status', 'coordinates', 'blocked', 'expert_id', 'company_name']);
        $locationQuery = Location::select(['id', 'coordinates', 'company_id']);

        if ($request->query('expert')) {
            $leadQuery->where('expert_id', '=', $request->query('expert'));
        }
        if ($request->query('status')) {
            $leadQuery->where('status', '=', $request->query('status'));
        }
        if($request->query('bounds')){
            $bounds = $request->query('bounds');
            $bounds = explode('),', $bounds);
            $bounds = array_map(function($value){
                $value = trim($value, '\t\n\r () ');
                $chunks = explode(', ', $value);
                return implode(' ', [$chunks[1], $chunks[0]]);
            }, $bounds);
            $bounds = implode(',', $bounds);
            $leadQuery->whereRaw("ST_Contains(ST_Envelope(GeomFromText('LINESTRING($bounds)')), `geo_coordinates`)");
            $locationQuery->whereRaw("ST_Contains(ST_Envelope(GeomFromText('LINESTRING($bounds)')), `geo_coordinates`)");
        }

        if ($request->query('type') == 'company') {
            $data = $locationQuery->get();
        } elseif ($request->query('type') == 'lead') {
            $data = $leadQuery->get();
        } else {
            $data = $locationQuery->get()->concat($leadQuery->get());
        }
        return new MapPinCollection($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}