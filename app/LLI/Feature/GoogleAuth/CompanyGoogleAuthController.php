<?php

namespace LocalheroPortal\LLI\Feature\GoogleAuth;

use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\CompanyGoogleAuth;

class CompanyGoogleAuthController extends Controller
{

    public function oauth2callback(Company $company, Request $request, Google_Client $client)
    {
        if (!$request->code || empty(Session::get('company'))) {
            return redirect($client->getRedirectUri());
        }
        $company = Company::find(Session::get('company'));
        Session::remove('company');

        $client->fetchAccessTokenWithAuthCode($request->code);
        $token = $client->getAccessToken();
        $companyGoogleAuth = $company->googleauth;

        if (is_null($companyGoogleAuth)) {
            $companyGoogleAuth = new CompanyGoogleAuth();
            $companyGoogleAuth->company_id = $company->id;
        }

        $companyGoogleAuth->access_token = $token['access_token'];
        if (isset($token['refresh_token'])) {
            $companyGoogleAuth->refresh_token = $token['refresh_token'];
        }
        $companyGoogleAuth->save();

//        return redirect(route('companies.show', ['company' => $company->id]));
        return redirect(route('location.unfinished'));
    }

    public function redirect(Company $company, Google_Client $client)
    {
        Session::put('company', $company->id);
        return redirect($client->createAuthUrl());
    }
}
