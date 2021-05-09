<?php

namespace LocalheroPortal\Core\Feature\ConvertLead;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\LLI\Feature\Location\LocationResource;
use LocalheroPortal\LLI\Http\Resources\CompanyResource;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;
use LocalheroPortal\Models\User\User;
use function Sentry\captureException;

class LeadCustomerConversionController extends Controller
{

    public function convertToNewCustomer(Lead $lead, Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns|unique:users,email|unique:companies,email',
            'comment' => 'required|string|min:5',
        ]);
        if ($lead->status == LeadState::CLOSED) {
            return Response::json(['errors' => ['Lead ist bereits ein Kunde.']], 400);
        }

        DB::beginTransaction();
        try {
            $user = $this->createUser($request->email, $lead->contact_name ?? $lead->company_name);
            $company = $this->createCompany($lead, $user);
            $location = $this->createLocation($lead, $company);
            $this->updateLeadState($lead);
            $this->addLeadComment($lead, $request->comment);
            $this->addLocationComment($location, $request->comment);
            Cache::forget(Auth::id().'lead');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::json(['errors' => [$e->getMessage()]], 500);
        }
        return Response::json(new LocationResource($location));
    }

    public function convertToExistingCustomer(Lead $lead, Company $company, Request $request)
    {
        $request->validate([
            'comment' => 'required|string|min:5',
        ]);
        if ($lead->status == LeadState::CLOSED) {
            return Response::json(['errors' => ['Lead ist bereits ein Kunde.']], 400);
        }

        try {
            $location = $this->createLocation($lead, $company);
            $this->updateLeadState($lead);
            $this->addLeadComment($lead, $request->comment);
            $this->addLocationComment($location, $request->comment);
            Cache::forget(Auth::id().'lead');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::json(['errors' => [$e->getMessage()]], 500);
        }
        return Response::json(new LocationResource($location));
    }

    protected function updateLeadState(Lead $lead)
    {
        $lead->status = LeadState::CLOSED;
        $lead->customer_since = now('Europe/Berlin');
        $lead->save();
    }

    protected function createUser(string $email, string $name)
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now('Europe/Berlin'),
            'password' => Hash::make(Str::random(10)),
            'remember_token' => Str::random(10),
            'api_token' => Str::random(60),
            'options' => [],
        ]);
    }

    protected function createCompany(Lead $lead, User $user): Company
    {
        $leadToCompanyMapper = new LeadToCompanyMapper();
        $company = $leadToCompanyMapper->mapLeadToCompany($lead);
        $company->email = $user->email;
        $company->user_id = $user->id;
        $company->save();

        return $company;
    }

    public function createLocation(Lead $lead, Company $company): Location
    {
        $leadToLocationConverter = new LeadToLocationMapper();
        $location = $leadToLocationConverter->mapLeadToLocation($lead);
        $location->company_id = $company->id;
        $location->states = [];
        $location->save();
        return $location;
    }

    public function addLocationComment(Location $location, string $comment)
    {
        $commentPrefix = "Wurde von ".Auth::user()->name." aus Lead konvertiert.";
        $location->comments()->save(new Comment([
            'reason' => CommentReason::CREATED,
            'body' => "$commentPrefix\n$comment",
            'user_id' => Auth::id(),
            'commentable_type' => 'location',
            'commentable_id' => $location->id,
        ]));
    }

    public function addLeadComment(Lead $lead, string $comment)
    {
        $commentPrefix = "Wurde von ".Auth::user()->name." zu Kunden Standort konvertiert.";
        $lead->comments()->save(new Comment([
            'reason' => CommentReason::CUSTOMER,
            'body' => "$commentPrefix\n$comment",
            'user_id' => Auth::id(),
            'commentable_type' => 'lead',
            'commentable_id' => $lead->id,
        ]));
    }
}
