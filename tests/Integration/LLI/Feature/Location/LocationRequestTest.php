<?php

namespace Tests\Integration\LLI\Feature\Location;

use Illuminate\Support\Facades\Validator;
use LocalheroPortal\LLI\Feature\Location\LocationRequest;
use Tests\Integration\IntegrationTestCase;

class LocationRequestTest extends IntegrationTestCase
{

    private array $requestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requestData = $this->getValidData();
    }

    private function getValidData(): array
    {
        return [
            'name' => 'Test Location',
            'address' => 'Test Strasse 5',
            'address_addition' => '1317',
            'postcode' => '76133',
            'state' => 'Baden-Württemberg',
            'country' => 'DE',
            'phone' => '+49 163 3006603',
            'mobilephone' => null,
            'fax' => null,
            'email' => 'test.location@localhero.de',
        ];
    }

    private function getValidator()
    {
        $request = new LocationRequest();
        return Validator::make($this->requestData, $request->rules(), $request->messages());
    }

    public function testValidData()
    {
        $validator = $this->getValidator();
        $this->assertFalse($validator->fails());
    }

    public function testCountryValidation()
    {
        $this->requestData['country'] = "Test";
        $this->assertTrue($this->getValidator()->fails());
    }

    public function testPhoneNumberValidation()
    {
        $this->requestData['phone'] = "0721 46139640 46139640";
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['mobilephone'] = "0721 46139640 46139640";
        $this->assertTrue($this->getValidator()->fails());
    }

    public function testRequiredValidation()
    {
        $this->requestData['name'] = null;
        $this->assertTrue($this->getValidator()->fails());
        $this->requestData['name'] = 'test';

        $this->requestData['phone'] = null;
        $this->assertTrue($this->getValidator()->fails());
        $this->requestData['phone'] = '0721 46139640';
    }

    public function testZipCodeValidation()
    {
        $this->requestData['postcode'] = '123456';
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['postcode'] = '123';
        $this->assertTrue($this->getValidator()->fails());
    }

    public function testEmailValidation()
    {
        $this->requestData['email'] = 'a';
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['email'] = 'a@';
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['email'] = '@a.a';
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['email'] = 'a@a';
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['email'] = 'a@a.';
        $this->assertTrue($this->getValidator()->fails());

        $this->requestData['email'] = 'a@.a';
        $this->assertTrue($this->getValidator()->fails());
    }

}