<?php

namespace LocalheroPortal\LLI\Feature\CompanyManagement;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\RoleType;

class CompanyWebController extends Controller
{

    public function create(Company $company)
    {
        return view('lli.company.CreateCompanyView', ['company' => $company]);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return Response::make('Company deleted');
    }

    public function edit(Company $company)
    {
        return view('lli.company.EditCompanyView', ['company' => $company]);
    }

    public function show(Company $company)
    {
        return view('lli.company.DetailCompanyView', ['company' => $company]);
    }

    public function index()
    {
        $user = Auth::user();
        //TODO: split up into different Controller
        if ($user->hasRole(RoleType::LLI_MANAGER) || $user->hasRole(RoleType::ADMIN)) {
            return view('lli.company.index');
        }
        if ($user->company) {
            return redirect(route('companies.show', $user->company->id));
        }
        //TODO: improve with role customer usage
        abort(403);
    }
}
