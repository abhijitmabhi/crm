<?php

namespace LocalheroPortal\Core\Feature\ExpertSettings;

use Illuminate\Http\Request;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\ExpertSettings;
use LocalheroPortal\Models\User\User;


class ExpertLocationController extends Controller
{

    public function getExpertLocation(Request $request)
    {
        $expertId = $request->query('expertId');
        $expert = User::whereId($expertId)->first();
        $this->checkExpert($expert);

        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
        $viewData = $this->getViewDate($expert, $expertSettings);

        return view('experts.admin.ExpertLocationView', $viewData);
    }

    private function checkExpert($user)
    {
        if (!$user) {
            abort(404, "Experte nicht gefunden.");
        }
        $userRoles = $user->roles->map(function ($role) {
            return $role->name;
        });
        if (!$userRoles->contains(RoleType::EXPERT)) {
            abort(400, "Nutzer ist kein Experte.");
        }
    }

    private function getViewDate($expert, $expertSettings)
    {
        return [
            'expertId' => $expert->id,
            'expertName' => $expert->name,
            'lat' => $expertSettings->coordinates['lat'],
            'long' => $expertSettings->coordinates['long'],
            'radius' => $expertSettings->radius
        ];
    }

    public function postExpertLocation(Request $request)
    {
        $expertId = $request->query('expertId');
        $expert = User::whereId($expertId)->first();
        $this->checkExpert($expert);

        $this->validate($request, [
            'lat'    =>  'required',
            'long'     =>  'required',
            'radius'     =>  'required'
        ]);

        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
        $expertSettings->coordinates = [
            "lat" => $request['lat'],
            "long" => $request['long']
        ];
        $expertSettings->radius = $request['radius'];
        $expertSettings->user_id = $expert->id;
        $expertSettings->save();

        return redirect()->route('admin.experts.index');
    }

}
