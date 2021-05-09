<?php

namespace LocalheroPortal\Core\Feature\ImportCustomers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Core\Util\UrlUtil;
use LocalheroPortal\Models\Batch;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;
use LocalheroPortal\Models\User\User;

class CustomerDataImporter
{

    public $errors = [];
    private $batch;
    private $authUser;
    public $companyIds = [];

    public function __construct(User $authUser)
    {
        $this->authUser = $authUser;
        $this->batch = new Batch(['type' => 'company']);
    }

    public function import($customerDataList)
    {
        foreach ($customerDataList as $data) {
            if (!$this->hasConflict($data)) {
                $this->importCustomerData($data);
            } else {
                $this->errors[] = 'Fehler beim Import ' . $data->name . ': Location besteht bereits';
            }
        }
        $this->batch->items = $this->companyIds;
        $this->batch->save();
        $this->commentCompany();
    }

    public function hasConflict($data)
    {
        $locationRepo = new LocationRepository();
        $conflictLocation = $locationRepo->getFirstByAddress($data->zipCode, $data->city, $data->street);
        return $conflictLocation != null;
    }

    private function importCustomerData($customerData)
    {
        $similarLocation = $this->findSimilarLocation($customerData);
        $similarCompany = $this->findSimilarCompany($customerData);
        DB::beginTransaction();
        try {
            if ($similarLocation) {
                $company = $similarLocation->company()->get()->first();
            } else if ($similarCompany) {
                $company = $similarCompany;
            } else {
                $user = $this->createOrFindUser($customerData);
                $company = $this->createCompany($user, $customerData);
            }
            $location = $this->createLocation($company, $customerData);
            if ($company->lead_id == null) {
                $lead = $this->findLead($customerData);
                if ($lead) {
                    $lead->status = LeadState::CLOSED;
                    $company->lead_id = $lead->id;
                    $location->lead_id = $lead->id;
                    $lead->save();
                    $company->save();
                    $location->save();
                }
            } else {
                $lead = $company->lead()->get()->first();
                if ($lead) {
                    $location->lead_id = $lead->id;
                    $location->save();
                }
            }
            DB::commit();
            $this->companyIds[] = $company->id;
        } catch (Exception $e) {
            $this->errors[] = "Import fehlgeschlagen: " . $e->getMessage();
            DB::rollBack();
        }
    }

    public function findSimilarLocation($data)
    {
        $repo = new LocationRepository();
        return $repo->getFirstSimilarLocation($data->name, $data->email, $data->website, $data->phone, $data->mobile);
    }

    public function findSimilarCompany($customerData)
    {
        $query = Company::whereName($customerData->name)
            ->orWhere('email', '=', $customerData->email);
        if ($customerData->website) {
            $domain = UrlUtil::getUrlDomain($customerData->website);
            $query = $query
                ->orWhere('url', 'like', '%' . $domain . '%');
        }
        if ($customerData->phone) {
            $query = $query
                ->orWhere('phone', '=', $customerData->phone)
                ->orWhere('phone_mobile', '=', $customerData->phone);
        }
        if ($customerData->mobile) {
            $query = $query
                ->orWhere('phone', '=', $customerData->mobile)
                ->orWhere('phone_mobile', '=', $customerData->mobile);
        }
        return $query->first();
    }

    private function createOrFindUser($customerData)
    {
        return User::firstOrCreate(
            ['email' => $customerData->email],
            [
                'name'              => $customerData->name,
                'email_verified_at' => now(),
                'password'          => Hash::make($customerData->userPassword)
            ]
        );
    }

    private function findLead($data)
    {
        $repo = new LeadRepository();
        return $repo->getFirstSimilarLead($data->email, $data->website, $data->phone, $data->mobile);
    }

    private function createCompany($user, $customerData)
    {
        $company = new Company();
        $company->user_id = $user->id;
        $company->name = $customerData->name;
        $company->url = $customerData->website;
        $company->email = $customerData->email;
        $company->contact_name = $customerData->contactPerson;
        $company->phone = $customerData->phone;
        $company->phone_mobile = $customerData->mobile;
        $company->street = $customerData->street;
        $company->zip = $customerData->zipCode;
        $company->city = $customerData->city;
        $company->save();
        return $company;
    }

    private function createLocation($company, $customerData)
    {
        $location = new Location();
        $location->company_id = $company->id;
        $location->name = $customerData->name;
        $location->address = $customerData->street;
        $location->postcode = $customerData->zipCode;
        $location->city = $customerData->city;
        $location->country = 'DE';
        $location->phone = $customerData->phone;
        $location->mobilephone = $customerData->mobile;
        $location->email = $customerData->email;
        $location->website = $customerData->website;
        $location->states = [LocationState::ACCESS_DATA_SENT, LocationState::ACTIVATED];
        $location->save();
        return $location;
    }

    private function commentCompany()
    {
        foreach ($this->companyIds as $companyId) {
            Comment::create([
                'reason'           => CommentReason::CREATED,
                'body'             => "Kunde wurde von {$this->authUser->name} importiert.",
                'user_id'          => $this->authUser->id,
                'commentable_type' => 'company',
                'commentable_id'   => $companyId,
                'date'             => now('Europe/Berlin'),
            ]);
        }
    }
}
