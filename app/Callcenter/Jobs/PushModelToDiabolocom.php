<?php

namespace LocalheroPortal\Callcenter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use LocalheroPortal\Models\Lead;

class PushModelToDiabolocom implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Lead
     */
    private $lead;
    private $baseurl = "https://rest-api-de1.engage.diabolocom.com";
    private $token = "eyJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InJvZW1lckBhbHBoYXRpZXIuZGUiLCJlbWFpbCI6IiIsImNyZWF0ZURhdGUiOjE1OTQ4ODQxODgyODMsInVzZXJfaWQiOjg5NX0.WQzvVnvYVgfN6esF-KB7BeL-GwdHdMo2uvlFfTzgGTI";
    private $campaignId = 122;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lead = $this->lead;
        dump(Http::withHeaders(['Authorization' => 'JWT ' . $this->token, 'Content-Type' => 'application/json'])
            ->post($this->baseurl . '/voice/campaigns/' . $this->campaignId . '/contacts',
                ['customFields' => [
                    'PLZ' => $lead->zip,
                    'Impressum' => $lead->website,
                    'GF / Inhaber' => $lead->contact_name,
                    'E-Mail-Adresse' => $lead->email,
                    'Strasse / Hausnummer' => $lead->street,
                    'Branche' => $lead->category,
                    'Name Firma' => $lead->company_name,
                    'Ort' => $lead->city,
                    'Telefon' => $lead->phone1,
                    'leadId' => $lead->id
                ]]
            )->body());
    }
}
