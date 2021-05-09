<?php

namespace LocalheroPortal\LLI\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\LLI\Http\Resources\LogResource;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\CompanyLog;

class CompanyLogController extends Controller
{
    /**
     * @param Company $company
     */
    public function index(Request $request, Company $company)
    {
        $per_page = isset($request->per_page) && is_numeric($request->per_page) ? (int) $request->per_page : 10;
        return LogResource::collection($company->logs()->paginate($per_page));
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
            'message' => 'string|nullable',
        ]);
        try {
            $path = Storage::disk('locationLogs')->putFile($company->id, $request->file);
            $log = new CompanyLog([
                'message'  => $request->message,
                'event'    => $request->event,
                'filename' => $path,
            ]);
            $company->logs()->save($log);
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::make($log, 201);
    }
}
