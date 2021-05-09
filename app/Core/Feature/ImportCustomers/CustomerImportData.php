<?php


namespace LocalheroPortal\Core\Feature\ImportCustomers;


class CustomerImportData
{
    public $name;
    public $contactPerson;
    public $street;
    public $zipCode;
    public $city;
    public $phone;
    public $mobile;
    public $email;
    public $website;
    public $userEmail;
    public $userPassword;

    public function getDataFields()
    {
        return array(
            &$this->name,
            &$this->contactPerson,
            &$this->street,
            &$this->zipCode,
            &$this->city,
            &$this->phone,
            &$this->mobile,
            &$this->email,
            &$this->website,
            &$this->userEmail,
            &$this->userPassword
        );
    }

    private function getRequiredDataFields()
    {
        return array(
            $this->name,
            $this->contactPerson,
            $this->street,
            $this->zipCode,
            $this->city,
            $this->userEmail,
            $this->userPassword
        );
    }

    public function isValid() {
        foreach ($this->getRequiredDataFields() as $dataField) {
            if (empty($dataField) || $dataField === "") {
                return false;
            }
        }
        return true;
    }
}