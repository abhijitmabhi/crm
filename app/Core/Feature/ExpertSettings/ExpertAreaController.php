<?php

namespace LocalheroPortal\Core\Feature\ExpertSettings;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\ExpertSettings;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;


class ExpertAreaController extends Controller
{

    public function getExpertArea(Request $request)
    {
        $expertId = $request->query('expertId');
        $expert = User::whereId($expertId)->first();
        $this->checkExpert($expert);

        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
        $viewData = $this->getViewDate($expert, $expertSettings);

        return view('experts.admin.ExpertAreaView', $viewData);
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

    private function getViewDate($expert, $expertSettings): array
    {
        $areaString = '';
        if ($expertSettings->area) {
            $areaString = implode(', ', $expertSettings->area);
        }
        return [
            'expertId' => $expert->id,
            'expertName' => $expert->name,
            'area' => $areaString,
            'conflicts' => []
        ];
    }

    public function saveExpertArea(Request $request)
    {
        $expertId = $request->query('expertId');
        $expert = User::whereId($expertId)->first();
        $this->checkExpert($expert);
        $inputAreas = $request['area'] ?? '';
        $areas = $this->parseAreas($inputAreas);

        if ($areas->isNotEmpty()) {
            $conflictFinder = new PostalCodeConflictFinder($this->getAllExpertAreas($expertId));
            $conflictAreas = $conflictFinder->getConflicts($areas);
            if ($conflictAreas->isNotEmpty()) {
                $experts = $this->getAllExperts($expertId);
                $conflictingExperts = $experts->filter(fn($expert) => $conflictAreas->intersect(collect($expert->expertSettings->area))->isNotEmpty());
                $conflicts = collect([]);
                foreach ($conflictingExperts as $conflictingExpert) {
                    $conflicts[$conflictingExpert->name] =
                        $conflictAreas->intersect(collect($conflictingExpert->expertSettings->area))->implode(', ');
                }

                $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
                $viewData = $this->getViewDate($expert, $expertSettings);
                $viewData['area'] = $inputAreas;
                $viewData['conflicts'] = $conflicts;

                return view('experts.admin.ExpertAreaView', $viewData);
            }
            $areaUser = $this->createOrFindAreaUser($expert);
            $this->moveExpertLeadsToAreaUser($expertId, $areaUser->id);
            $this->moveLeadsToExpertByArea($expertId, $areas);
        }

        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
        $expertSettings->area = $areas->toArray();
        $expertSettings->user_id = $expert->id;
        $expertSettings->save();

        return redirect()->route('admin.experts.index');
    }

    private function parseAreas(string $areas): Collection
    {
        if (!$areas) {
            return collect();
        }
        $formattedAreas = preg_replace('/[^0-9,*]/', '', $areas);
        $splittedAreas = collect(explode(',', $formattedAreas));
        return $splittedAreas->filter(fn($area) => $area);
    }

    private function getAllExpertAreas($exceptId)
    {
        $experts = $this->getAllExperts($exceptId);
        return $experts->map(fn($expert) => $expert->expertSettings->area)->flatten();
    }

    private function getAllExperts($exceptId)
    {
        return User::byRole(RoleType::EXPERT)
            ->where('is_active', '=', 1)
            ->where('id', '!=', $exceptId)
            ->whereHas('expertSettings', function ($query) {
                return $query->where('area', '!=', '[]');
            })->get();
    }

    private function createOrFindAreaUser($expert): User
    {
        return User::firstOrCreate(
            ['email' => 'area.'.$expert->email],
            [
                'first_name' => 'Gebiet',
                'last_name' => $expert->last_name,
                'email_verified_at' => now(),
                'password' => $expert->password
            ]
        );
    }

    private function moveExpertLeadsToAreaUser($expertId, $areaUserId)
    {
        Lead::whereExpertId($expertId)
            ->whereIn('status', [
                LeadState::OPEN, LeadState::INVALID, LeadState::NOT_REACHED, LeadState::NO_INTEREST
            ])
            ->whereDoesntHave('allCalendarEvents')
            ->update(['expert_id' => $areaUserId]);
    }

    private function moveLeadsToExpertByArea($expertId, $areas)
    {
        $regexAreas = $areas->filter(fn($area) => str_contains($area, '*'));
        $exactAreas = $areas->filter(fn($area) => !str_contains($area, '*'));
        $sqlRegexAreas = $regexAreas->map(fn($area) => str_replace('*', '%', $area));

        Lead::query()
            ->whereIn('status', [
                LeadState::OPEN, LeadState::INVALID, LeadState::NOT_REACHED, LeadState::NO_INTEREST
            ])
            ->where(function (Builder $query) use ($exactAreas, $sqlRegexAreas) {
                if ($exactAreas) {
                    $query = $query->orWhereIn('zip', $exactAreas);
                }
                foreach ($sqlRegexAreas as $regexArea) {
                    $query = $query->orWhere('zip', 'like', $regexArea);
                }
                return $query;
            })
            ->whereDoesntHave('allCalendarEvents')
            ->update(['expert_id' => $expertId]);
    }

}
