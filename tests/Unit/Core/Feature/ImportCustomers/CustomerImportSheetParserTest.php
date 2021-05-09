<?php


namespace Tests\Unit\Core\Feature\ImportCustomers;


use LocalheroPortal\Core\Feature\ImportCustomers\CustomerImportData;
use LocalheroPortal\Core\Feature\ImportCustomers\CustomerImportSheetParser;
use Tests\Unit\UnitTestCase;

class CustomerImportSheetParserTest extends UnitTestCase
{

    public function testFormatPhoneNumber()
    {
        $parser = new CustomerImportSheetParser();

        $testWrongPrefix = $parser->formatPhoneNumber("49 1577 1974869");
        self::assertEquals("+49 1577 1974869", $testWrongPrefix);

        $testRegular = $parser->formatPhoneNumber("01577 1974869");
        self::assertEquals("+49 1577 1974869", $testRegular);

        $testContainingSlash = $parser->formatPhoneNumber("01577/1974869");
        self::assertEquals("+49 1577 1974869", $testContainingSlash);
    }

    public function testFormatParsedData() {
        $parser = new CustomerImportSheetParser();

        $data = new CustomerImportData();
        $data->phone = "abc";
        $parser->parsedData[] = $data;

        $parser->formatParsedData();
        self::assertCount(0, $parser->parsedData);
        self::assertCount(1, $parser->errors);
    }
}