<?php

namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Http\Request;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class MapController extends Controller
{
    /**
     * @param $request
     */
    public function __invoke(Request $request)
    {
        $experts = User::byRole(RoleType::EXPERT)->get();

        $filter = $request->query('filter');

        $icons = [
            "open" => [
                'file' => asset('img/mapMarkers/mapMarkerOpen.png'),
                'status' => 'Offen'
            ],
            "notReached" => [
                'file' => asset('img/mapMarkers/mapMarkerNotReached.png'),
                'status' => 'Nicht erreicht'
            ],
            "recall" => [
                'file' => asset('img/mapMarkers/mapMarkerRecall.png'),
                'status' => 'Rückruf / Termin nötig'
            ],
            "appointment" => [
                'file' => asset('img/mapMarkers/mapMarkerAppointment.png'),
                'status' => 'Termin'
            ],
            "noInterest" => [
                'file' => asset('img/mapMarkers/mapMarkerNoInterest.png'),
                'status' => 'Kein Interesse / Zu viele Versuche'
            ],
            "blacklist" => [
                'file' => asset('img/mapMarkers/mapMarkerBlacklist.png'),
                'status' => 'Blacklisted'
            ],
            "competitionProtection" => [
                'file' => asset('img/mapMarkers/mapMarkerCompetitionProtection.png'),
                'status' => 'Konkurrenzschutz'
            ],
            "customer" => [
                'file' => asset('img/mapMarkers/mapMarkerCustomer.png'),
                'status' => 'Kunde'
            ],
        ];

        $states = LeadState::asSelectArray();

        return view('LeadMapView', compact('icons', 'filter', 'states', 'experts'));
    }
}