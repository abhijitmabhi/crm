<?php


namespace LocalheroPortal\Core\Feature\Search;


use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LLI\Company;

class SearchWebController extends Controller
{

    public function search(Request $request)
    {
        $active = $request->input('active') ?? 'lead';
        $searchTerm = $request->searchTerm ?? '';
        $forbiddenCharacters = [
            '-', '=', '&&', '||', '>', '<', '!', '(', ')', '{', '}', '[', ']', '^', '"', '~', '*', '?', ':', '\\', '/'
        ];
        $searchTerm = str_replace($forbiddenCharacters, ' ', $searchTerm);
        if (PhoneUtil::isValidPhoneNumber($searchTerm)) {
            $searchTerm = PhoneUtil::formatPhoneNumber($searchTerm);
        }

        $leads = collect();
        $companies = collect();
        if ($searchTerm) {
            $leads = Lead::search($searchTerm)->paginate(100)->appends([
                'searchTerm' => $searchTerm,
                "active" => "lead",
                'company' => $request->input('company')
            ]);
            $companies = Company::search($searchTerm)->paginate(100)->appends([
                'searchTerm' => $searchTerm,
                "active" => "company",
                'lead' => $request->input('lead')
            ]);
        }
        return view('callcenter.SearchResultView',
            compact('leads', 'searchTerm', 'companies', "active"));
    }

}