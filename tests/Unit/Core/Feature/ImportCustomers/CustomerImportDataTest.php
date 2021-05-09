<?php


namespace Tests\Unit\Core\Feature\ImportCustomers;


use LocalheroPortal\Core\Feature\ImportCustomers\CustomerImportData;
use Tests\Unit\UnitTestCase;

class CustomerImportDataTest extends UnitTestCase
{

    public function testDataFieldsChange()
    {
        $customerImportData = new CustomerImportData();
        $dataFields = $customerImportData->getDataFields();
        $dataFields[0] = "test";
        self::assertEquals("test", $customerImportData->name);
    }

    public function testCustomerDataValidation()
    {
        $customerImportData = new CustomerImportData();
        $dataFields = $customerImportData->getDataFields();

        $dataFields[0] = "test";
        self::assertFalse($customerImportData->isValid());

        foreach ($dataFields as $i => $dataField) {
            $dataFields[$i] = "test";
        }
        self::assertTrue($customerImportData->isValid());
    }
}