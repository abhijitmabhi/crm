<?php

namespace Tests\Integration\Core\Feature\CreateLead;

use Illuminate\Support\Facades\Validator;
use LocalheroPortal\Core\Feature\CreateLead\CreateLeadRequest;
use LocalheroPortal\Models\Lead;
use Tests\Integration\IntegrationTestCase;

class CreateLeadRequestTest extends IntegrationTestCase
{

    private $leadData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->leadData = $this->getValidData();
    }

    private function getValidData()
    {
        return [
            "company_name" => "TESTCOMPANY",
            "place_id" => null,
            "street" => "MÜHLENKAMP 3",
            "zip" => "49685",
            "city" => "BÜHREN",
            "title" => null,
            "contact_name" => "FABIANLUKASSEN",
            "additional_contacts" => null,
            "phone1" => "+49 163 3006603",
            "phone2" => null,
            "email" => "FABIANLUKASSEN@TESTEN.DE",
            "category" => "Hotel",
            "sub_category" => null,
            "website" => "https://www.test.de/",
            "status" => 1,
            "expert_status" => 0,
            "coordinates" => null,
            "expert_id" => 1,
            "agent_id" => null,
            "blocked" => 0,
            "important_note" => null

        ];
    }

    private function getValidator()
    {
        $request = new CreateLeadRequest();
        return Validator::make($this->leadData, $request->rules(), $request->messages());
    }

    public function testValidData()
    {
        $validator = $this->getValidator();
        $this->assertFalse($validator->fails());
    }

    public function testPhoneNumberValidation()
    {
        $this->leadData['phone1'] = null;
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['phone1'] = "0721 46139640 46139640";
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['phone1'] = Lead::query()->whereNotNull('phone1')->first()->value('phone1');
        $this->assertTrue($this->getValidator()->fails());
    }

    public function testRequiredValidation()
    {
        $this->leadData['company_name'] = null;
        $this->assertTrue($this->getValidator()->fails());
        $this->leadData['company_name'] = 'test';

        $this->leadData['contact_name'] = null;
        $this->assertTrue($this->getValidator()->fails());
        $this->leadData['contact_name'] = 'test';

        $this->leadData['category'] = null;
        $this->assertTrue($this->getValidator()->fails());
        $this->leadData['category'] = 'test';
    }

    public function testZipCodeValidation()
    {
        $this->leadData['zip'] = '123456';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['zip'] = '123';
        $this->assertTrue($this->getValidator()->fails());
    }

    public function testEmailValidation()
    {
        $this->leadData['email'] = 'a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['email'] = 'a@';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['email'] = '@a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['email'] = 'a@a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['email'] = 'a@a.';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['email'] = 'a@.a';
        $this->assertTrue($this->getValidator()->fails());
    }

    public function testWebsiteValidation()
    {
        $this->leadData['website'] = 'a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['website'] = 'a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['website'] = 'w.a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['website'] = 'www.a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['website'] = 'h://www.a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['website'] = 'h:/www.a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->leadData['website'] = 'http://www.a.a';
        $this->assertFalse($this->getValidator()->fails());
    }

}