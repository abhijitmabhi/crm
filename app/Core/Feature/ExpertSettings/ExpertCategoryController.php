<?php

namespace LocalheroPortal\Core\Feature\ExpertSettings;

use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Models\ExpertSettings;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class ExpertCategoryController extends Controller
{

    public function getExpertCategoryView(Request $request)
    {
        $expert = User::whereId($request->expert)->first();
        $allAgents = User::byRole(RoleType::CALL_CENTER_AGENT)
            ->where('is_active', '=', true)
            ->where('block_login', '=', false)
            ->get();
        $expertAgents = $expert->callagents->map(function ($rel) {
            return $rel->agent;
        });

        $leadRepo = new LeadRepository();
        $categoryStats = $leadRepo->countByStatusGroupByCategoryForExpert($expert->id);
        $nullCategory = $categoryStats->firstWhere('category', null);
        if ($nullCategory) {
            $nullCategory->category = 'Unbekannt';
        }
        $categories = $categoryStats->map(function ($data) {
            return $data->category;
        });

        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();

        $viewData = [
            'expert' => $expert,
            'allAgents' => $allAgents,
            'expertAgents' => $expertAgents,
            'categories' => $categories->values(),
            'expertCategories' => collect($expertSettings->categories),
            'excludedCategories' => collect($expertSettings->excluded_categories),
            'categoryStats' => $categoryStats
        ];

        return view('experts.admin.ExpertCategoryView', $viewData);
    }

    public function prioritizeCategories(Request $request)
    {
        $expertId = $request->expertId;
        $expert = User::whereId($expertId)->first();
        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
        $expertSettings->user_id = $expertId;
        if (is_string($request->categories)) {
            $expertSettings->categories = [$request->categories];
        } else {
            $expertSettings->categories = $request->categories;
            $expertSettings->excluded_categories = array_values(array_diff($expertSettings->excluded_categories, $expertSettings->categories));
        }
        $expertSettings->save();

        return redirect('admin/experts');
    }

    public function excludeCategories(Request $request)
    {
        $expertId = $request->expertId;
        $expert = User::whereId($expertId)->first();
        $expertSettings = $expert->expertSettings()->first() ?? ExpertSettings::getDefaultExpertSettings();
        $expertSettings->user_id = $expertId;
        if (is_string($request->categories)) {
            $expertSettings->excluded_categories = [$request->categories];
        } else {
            $expertSettings->excluded_categories = $request->categories;
            $expertSettings->categories = array_values(array_diff($expertSettings->categories, $expertSettings->excluded_categories));
        }
        $expertSettings->save();

        return redirect('admin/experts');
    }
}
