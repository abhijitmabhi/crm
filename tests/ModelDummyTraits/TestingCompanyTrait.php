<?php

namespace Tests\ModelDummyTraits;

use LocalheroPortal\Models\LLI\Company;

trait TestingCompanyTrait
{
    public function createTestCompany() {
        $company = new Company;
        $company->name = "TEST COMPANY";
        $company->email = "TESTEMAIL";
        $company->save();

        return $company;
    }

    public function deleteTestCompany(Company $company) {
        $company->forceDelete();
    }
}
