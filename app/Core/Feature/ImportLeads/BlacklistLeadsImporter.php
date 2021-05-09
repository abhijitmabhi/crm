<?php

namespace LocalheroPortal\Core\Feature\ImportLeads;

use Exception;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\User;

class BlacklistLeadsImporter
{

    public $errors = [];
    public $leadIds = [];

    public function __construct(User $authUser)
    {
        $this->authUser = $authUser;
    }

    public function import($dataList)
    {
        foreach ($dataList as $data) {
            $conflict = $this->getConflict($data);
            if (!$conflict) {
                $this->importData($data);
            } else {
                $this->errors[] = 'Fehler beim Import ' . $data->name . ': Lead besteht bereits';
                $conflict->status = LeadState::BLACKLIST;
                $conflict->deleted_at = null;
                $conflict->save();
            }
        }
        $this->commentLeads();
    }

    public function getConflict($data)
    {
        return Lead::withTrashed()
            ->orWhere(function ($query) use ($data) {
                return $query->whereStreet($data->street)
                    ->where('zip', $data->zipCode)
                    ->where('city', $data->city);
            })->orWhere('phone1', $data->phone)
            ->first();
    }

    private function importData($data)
    {
        DB::beginTransaction();
        try {
            $lead = $this->createLead($data);
            DB::commit();
            $this->leadIds[] = $lead->id;
        } catch (Exception $e) {
            $this->errors[] = "Import fehlgeschlagen: " . $e->getMessage();
            DB::rollBack();
        }
    }

    private function createLead($customerData)
    {
        $lead = new Lead();
        $lead->company_name = $customerData->name;
        $lead->website = $customerData->website;
        $lead->email = $customerData->email;
        $lead->contact_name = $customerData->contactPerson;
        $lead->phone1 = $customerData->phone;
        $lead->phone2 = $customerData->mobile;
        $lead->street = $customerData->street;
        $lead->zip = $customerData->zipCode;
        $lead->city = $customerData->city;
        $lead->status = LeadState::BLACKLIST;
        $lead->save();
        return $lead;
    }

    private function commentLeads()
    {
        foreach ($this->leadIds as $leadId) {
            Comment::create([
                'reason'           => CommentReason::CREATED,
                'body'             => "Lead wurde von {$this->authUser->name} als Blacklist importiert.",
                'user_id'          => $this->authUser->id,
                'commentable_type' => 'lead',
                'commentable_id'   => $leadId,
                'date'             => now('Europe/Berlin'),
            ]);
        }
    }
}
