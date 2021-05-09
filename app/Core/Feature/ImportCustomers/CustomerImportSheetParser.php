<?php

namespace LocalheroPortal\Core\Feature\ImportCustomers;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\ReaderInterface;
use Illuminate\Support\Facades\Storage;
use libphonenumber\NumberParseException;
use LocalheroPortal\Core\Util\PhoneUtil;

class CustomerImportSheetParser
{

    public $parsedData = [];
    public $errors = [];

    public function parse($uploadedFile)
    {
        $file = $this->storeImportSheet($uploadedFile);
        try {
            $reader = $this->getImportSheetReader($file);
        } catch (IOException $e) {
            $this->errors[] = "Importieren fehlgeschlagen: " . $e->getMessage();
            return;
        } catch (UnsupportedTypeException $e) {
            $this->errors[] = "Importieren fehlgeschlagen: " . $e->getMessage();
            return;
        }
        $this->parseFile($reader);
        $this->formatParsedData();

        $reader->close();
        Storage::disk('public')->delete($file);
    }

    public function storeImportSheet($uploadedFile)
    {
        $extension = $uploadedFile->getClientOriginalExtension();
        $storedFile = $uploadedFile->storeAs('sheets', 'sheet' . time() . '.' . $extension);
        return $storedFile;
    }

    /**
     * @param $storedFile
     * @return ReaderInterface
     * @throws IOException
     * @throws UnsupportedTypeException
     */
    private function getImportSheetReader($storedFile)
    {
        $reader = ReaderEntityFactory::createReaderFromFile($storedFile);
        $path = Storage::disk('public')->path($storedFile);
        $reader->open($path);
        return $reader;
    }

    private function parseFile($reader)
    {
        foreach ($reader->getSheetIterator() as $sheet) {
            $firstRow = true;
            foreach ($sheet->getRowIterator() as $rowNr => $row) {
                if ($firstRow) {
                    $firstRow = false;
                    continue;
                }

                $cells = $row->getCells();
                $customerData = new CustomerImportData();
                $customerDataFields = $customerData->getDataFields();

                foreach ($cells as $i => $cell) {
                    if ($cell->getValue() != "") {
                        $customerDataFields[$i] = $cell->getValue();
                    }
                }

                if ($customerData->isValid()) {
                    array_push($this->parsedData, $customerData);
                } else {
                    $this->errors[] = "Fehlende Daten in Zeile $rowNr";
                }
            }
        }
    }

    public function formatParsedData()
    {
        foreach ($this->parsedData as $i => $data) {
            try {
                if (!empty($data->phone)) {
                    $data->phone = $this->formatPhoneNumber($data->phone);
                }
                if (!empty($data->mobile)) {
                    $data->mobile = $this->formatPhoneNumber($data->mobile);
                }
            } catch (NumberParseException $e) {
                $this->errors[] = 'Fehler beim Import ' . $data->name . ': ' . $e->getMessage();
                unset($this->parsedData[$i]);
            }
        }
    }

    /**
     * @param $phone
     * @return string
     * @throws NumberParseException
     */
    public function formatPhoneNumber($phone) {
        $phone = preg_replace('/^(49)/', '+49', $phone);
        $phone = PhoneUtil::formatPhoneNumber($phone);
        return $phone;
    }
}
