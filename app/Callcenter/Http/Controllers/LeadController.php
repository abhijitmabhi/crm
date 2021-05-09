<?php

namespace LocalheroPortal\Callcenter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LocalheroPortal\Models\LeadExpertAcceptance;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class LeadController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        return view('leads.index');
    }

    public function show(Request $request, Lead $lead)
    {
        return view('leads.show', ['lead' => $lead]);
    }

    public function accept(Lead $lead)
    {
        if ($lead->expert_status === LeadExpertAcceptance::OPEN) {
            $lead->expert_status = LeadExpertAcceptance::ACCEPTED;
            $lead->save();
        }
        return redirect()->route('leads.show', ['lead' => $lead->id])->with('success', 'Lead akzeptiert');
    }

    public function reject(Lead $lead)
    {
        if ($lead->expert_status === LeadExpertAcceptance::OPEN) {
            $lead->expert_status = LeadExpertAcceptance::REJECTED;
            $lead->save();
        }
        return redirect()->route('leads.show', ['lead' => $lead->id])->with('success', 'Lead abgelehnt');
    }

    public function showWithoutContactName()
    {
        return view('leads.invalid');
    }

    public function batchImportFromGooglePlaces(Request $request)
    {
        $selectedExpert = $request->has('selected_expert') ? $request->get('selected_expert') : false;
        $selectedCity = $request->has('selected_city') ? $selectedCity = $request->get('selected_city') : false;
        $cities = collect([
            "Berlin",
            "Hamburg",
            "München",
            "Köln",
            "Frankfurt am Main",
            "Stuttgart",
            "Düsseldorf",
            "leipzig",
            "Dortmund",
            "Essen",
            "Bremen",
            "Dresden",
            "Hannover",
            "Nürnberg",
            "Duisburg",
            "Bochum",
            "Wuppertal",
            "Bonn",
            "münster",
            "karlsruhe",
            "Mannheim",
            "augsburg",
            "Wiesbaden",
            "Mönchengladbach",
            "Gelsenkirchen",
            "Braunschweig",
            "Kiel",
            "Aachen",
            "Chemnitz",
            "Halle (Saale)",
            "Magdeburg",
            "Freiburg im Braisgau",
            "Krefeld",
            "Lübeck",
            "Mainz",
            "Erfurt",
            "Oberhausen",
            "Rostock",
            "Kasse",
            "Hagen",
            "Saarbrücken",
            "Hamm",
            "Potsdam",
            "Ludwigshafen am Rhein",
            "Mülheim an der Ruhr",
            "Oldenburg",
            "Osnabrück",
            "Leverkusen",
            "Heidelberg",
            "Solingen",
            "Darmstadt",
            "Herne",
            "Neuss",
            "Regensburg",
            "Paderborn",
            "Ingolstadt",
            "Offenbach am Main",
            "Würzburg",
            "Fürth",
            "Ulm",
            "Heilbronn",
            "Pforzheim",
            "Wolfsburg",
            "Göttingen",
            "Bottrop",
            "Reutlingen",
            "Koblenz",
            "Bremerhaven",
            "Recklinghausen",
            "Bergisch Gladbach",
            "Erlangen",
            "Jena",
            "Remscheid",
            "Trier",
            "Salzgitter",
            "Moers",
            "Siegen",
            "Hildesheim",
            "Cottbus",
            "Gütersloh",
        ]);
        if (Auth::user()->hasRole('expert')) {
            $ok = false;
            $otherRoles = ['admin', 'callcenter-agent', 'callcenter-supervisor'];
            foreach ($otherRoles as $otherRole) {
                if ($ok = Auth::user()->hasRole($otherRole)) {
                    break;
                }
            }
            if (!$ok) {
                $experts = collect([(object)['name' => Auth::user()->name, 'id' => Auth::id()]]);
            }
        }
        $experts = $experts ?? User::byRole(RoleType::EXPERT)->select('last_name', 'first_name', 'id')->get();
        $google_categories = collect(json_decode(file_get_contents(base_path('resources/google/relevant_categories.json')), false));
        return view('callcenter.BatchImportGooglePlaces', [
            'experts'        => $experts,
            'categories'     => $google_categories,
            'selectedExpert' => $selectedExpert,
            'selectedCity'   => $selectedCity,
            'cities'         => $cities,
        ]);
    }
}
