<?php

namespace LocalheroPortal\LLI\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\CompanyLog;

class CompanyLogController extends Controller
{
    /**
     * @param Company $company
     */
    public function index(Company $company)
    {
        return view('lli.logs.index', ['company' => $company]);
    }

    /**
     * Company Logs should only be accessible by authorized users
     * @param Company    $company
     * @param CompanyLog $log
     */
    public function show(Company $company, CompanyLog $log)
    {
        return Storage::disk('companyLogs')->download($log->filename);
    }

    /**
     * Authorized users should be able to store new logs
     * @param Request $request
     * @param Company $company
     */
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'file'    => 'file|required',
            'event'   => 'string|required',
            'message' => 'string|nullable'
        ]);
        try {
            $path = Storage::disk('locationLogs')->putFile($company->id, $request->file);
            $log = new CompanyLog([
                'message'  => $request->message,
                'event'    => $request->event,
                'filename' => $path
            ]);
            $company->logs()->save($log);
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::make($log, 201);
    }
}
