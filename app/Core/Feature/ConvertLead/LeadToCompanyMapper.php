<?php


namespace LocalheroPortal\Core\Feature\ConvertLead;


use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LLI\Company;

class LeadToCompanyMapper
{

    public function mapLeadToCompany(Lead $lead): Company
    {
        $data = $lead->only(['street', 'zip', 'city', 'email']);
        $company = new Company($data);
        $company->name = $lead->company_name;
        $company->phone = $lead->phone1;
        if ($lead->website) {
            $company->url = $this->getBaseUrl($lead->website);
        }

        return $company;
    }

    public function getBaseUrl($url)
    {
        $urlComponents = parse_url($url);
        return $urlComponents['host'];
    }
}