<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Admin;

use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Callagent;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class ExpertController extends Controller
{
    /**
     * @param  Request  $request
     */
    public function assign(Request $request)
    {
        $expertId = $request->expertId;
        Callagent::where('expert_id', '=', $expertId)->delete();
        if ($request->agentIds) {
            foreach ($request->agentIds as $agentId) {
                $callagent = new Callagent();
                $callagent->expert_id = $expertId;
                $callagent->agent_id = $agentId;
                $callagent->save();
            }
        }

        return back();
    }

    public function index()
    {
        $experts = User::withTrashed()->byRole(RoleType::EXPERT)
            ->with('callagents')
            ->get(['id', 'first_name', 'last_name', 'name', 'deleted_at', 'is_active', 'block_login']);

        $experts = $experts->sortBy(function ($expert) {
            if ($expert->trashed() || !$expert->is_active || $expert->block_login) {
                return $expert->last_name ? "zz".$expert->last_name : "zzz".$expert->first_name;
            }

            if ($expert->last_name) {
                return $expert->last_name;
            }

            return "z".$expert->first_name;
        });

        $viewData = ['experts' => $experts->values()];
        return view('experts.admin.ExpertsManagementView', $viewData);
    }

    /**
     * @param  Request  $request
     * @param  User  $expert
     */
    public function show(Request $request, User $expert)
    {
        $data = [
            'expert' => $expert,
            'appointments' => Lead::forUser($expert)->stateAppointment(),
        ];
        if ($request->appointment) {
            $data['lead'] = Lead::find($request->appointment);
        }

        return view('experts.appointments', $data);
    }
}
