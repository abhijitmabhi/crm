<?php

namespace LocalheroPortal\Core\Feature\ImportLeads;

use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use libphonenumber\NumberParseException;
use LocalheroPortal\Callcenter\Jobs\DeleteSheet;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\Batch;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\User\User;

class LeadImporter
{
    /**
     * @var Collection
     */
    private $errors = null;

    /**
     * @var object
     */
    private $errorFile = [
        'name' => null,
        'path' => null,
        'url'  => null,
    ];

    /**
     * @var Batch
     */
    private $batch;

    /**
     * The User who started the import
     * @var User
     */
    private $authUser;

    private $leads = [];

    /**
     * @var mixed
     */
    private $input;

    /**
     * @var int
     */
    private $successes = 0;

    /**
     * @var array
     */
    private $tableHeaders = [
        'company_name',
        'street',
        'zip',
        'city',
        'title',
        'contact_name',
        'additional_contacts',
        'phone1',
        'phone2',
        'email',
        'website',
        'category',
    ];

    /**
     * @param string $inputFile
     */
    public function batchImport($inputFile)
    {
        $this->input = $inputFile;
        $this->parseFile();
        $this->commentLeads();
        $this->createErrorFile();
        DeleteSheet::dispatch($this->errorFile['name'])
            ->delay(now()->addMinutes(15));
    }

    public function __construct(User $authUser)
    {
        $this->errors = new Collection();
        $this->errorFile['name'] = uniqid('import_errors_') . '.ods';
        $this->errorFile['path'] = Storage::disk('public')->path('sheets/' . $this->errorFile['name']);
        $this->errorFile['url']  = asset('storage/sheets/' . $this->errorFile['name']);
        $this->authUser = $authUser;
        $this->batch = new Batch(['type' => 'lead']);
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors->map(function ($error) {
            return 'Lead Import für <strong>' . $error['title'] . '</strong> gescheitert: ' . $error['error'];
        });
    }

    /**
     * @return mixed
     */
    public function getSuccesses()
    {
        return $this->successes;
    }

    public function hasErrors()
    {
        return 0 < $this->errors->count();
    }

    private function hasConflicts($attributes)
    {
//        $leadRepo = new LeadRepository();
//        $similarLead = $leadRepo
//            ->getFirstSimilarLead($attributes['email'], $attributes['website'], $attributes['phone1'], $attributes['phone2']);
//        $locationRepo = new LocationRepository();
//        $similarLocation = $locationRepo
//            ->getFirstSimilarLocation($attributes['name'], $attributes['email'], $attributes['website'], $attributes['phone1'], $attributes['phone2']);
//        return $similarLead || $similarLocation;
        $similarLead = Lead::wherePhone1($attributes['phone1'])->first();
        $similarLocation = Location::wherePhone($attributes['phone1'])->first();
        return $similarLead || $similarLocation;
    }

    /**
     * @param array $attributes
     * @param int   $expert_id
     */
    public function importLead($attributes, $expert_id)
    {
        if ($this->hasConflicts($attributes)) {
            $this->logError($attributes, 'Doppelter Eintrag');
            return;
        }
        try {
            $lead = new Lead($attributes);
            $lead->expert_id = $expert_id;
            $lead->blocked = intval(Request::input('private-lead'));
            $lead->save();
            $this->leads[] = $lead->id;
            $this->successes++;
        } catch (Exception $e) {
            $message = $e->getMessage();
            if (Str::contains($message, "doesn't have a default value")) {
                $field = [];
                $lead->error = preg_match("/Field \'(.*)\' doesn't/", $message, $field);
                $lead->error = '<strong>Fehler beim Import</strong> Leeres Feld: ' . __("attributes.$field[1]");
                $this->logError($attributes, 'Leeres Feld: ' . __("attributes.$field[1]"));
            } else {
                $lead->error = $message;
                $this->logError($attributes, $message);
            }
        }
    }

    /**
     * @param  array   $attributes
     * @return mixed
     */
    private function formatAttributes($attributes)
    {
        $attributes = array_combine($this->tableHeaders, $attributes);
        try {
            $attributes['phone1'] = PhoneUtil::formatPhoneNumber($attributes['phone1']);
        } catch (NumberParseException $e) {
            $this->logError($attributes, $e->getMessage());
            return false;
        }
        return $this->removeEmptyFields($attributes);
    }

    private function parseFile()
    {
        $file = $this->input->storeAs('sheets', 'sheet' . time() . '.' . $this->input->getClientOriginalExtension());
        $reader = ReaderEntityFactory::createReaderFromFile($file);
        $path = Storage::disk('public')->path($file);
        $reader->open($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            $firstRow = true;
            foreach ($sheet->getRowIterator() as $rowNr => $row) {
                if ($firstRow) {
                    $firstRow = false;
                    continue;
                }
                $rowItems = [];
                foreach ($row->getCells() as $cellOffset => $cell) {
                    if ($cellOffset >= count($this->tableHeaders)) {
                        break;
                    }
                    if (7 === $cellOffset) {
                        // Can this produce unforseeable errors?
                        $nr = (string)$cell->getValue();
                        $rowItems[] = preg_replace('/^(49)/', '+49', $nr);
                    } else {
                        $rowItems[] = (string) $cell->getValue();
                    }
                }
                $missingAttrs = [];
                foreach ($this->tableHeaders as $i => $header) {
                    if (!isset($rowItems[$i])) {
                        $missingAttrs[] = __("attributes.$header");
                    }
                }
                if (count($missingAttrs)) {
                    $this->logError(
                        ['company_name' => isset($rowItems[0]) ? $rowItems[0] : 'Unbekannter Lead'],
                        "Fehlende Felder in Zeile $rowNr:" . implode(', ', $missingAttrs)
                    );
                } else {
                    $attributes = $this->formatAttributes($rowItems);
                    if ($attributes) {
                        $this->importLead($attributes, intval(Request::input('expert')));
                    }
                }
            }
        }
        $this->batch->items = $this->leads;
        $this->batch->options = ['expert_id' => intval(Request::input('expert'))];
        $this->batch->save();
        $reader->close();
        Storage::disk('public')->delete($file);
    }

    /**
     * @param array $fields
     */
    private function removeEmptyFields(array $fields): array
    {
        return collect($fields)->filter(function ($value, $key) {
            return !empty($value);
        })->toArray();
    }

    private function commentLeads()
    {
        foreach ($this->leads as $leadId) {
            Comment::create([
                'reason'           => CommentReason::CREATED,
                'body'             => "Wurde von Data Analyst {$this->authUser->name} importiert.",
                'user_id'          => $this->authUser->id,
                'commentable_type' => 'lead',
                'commentable_id'   => $leadId,
                'date'             => now('Europe/Berlin'),
            ]);
        }
    }

    protected function logError(array $attributes, string $error, array $other = [])
    {
        $error = array_merge([
            'title'      => $attributes['company_name'] ?? 'Unbekannter Lead',
            'error'      => $error,
            'attributes' => $attributes
        ], $other);

        $this->errors->push($error);
    }

    protected function createErrorFile()
    {
        $file = WriterEntityFactory::createODSWriter();
        $file->openToFile($this->errorFile['path']);
        $style = (new StyleBuilder())->setFontBold()->build();
        $file->addRow(WriterEntityFactory::createRowFromArray(
            array_merge(['Fehler'], array_map(function ($header) {
                return __("attributes.$header");
            }, $this->tableHeaders)),
            $style
        ));
        $errorStyle = (new StyleBuilder())->setFontColor(Color::RED)->build();
        $this->errors->each(function (array $error) use (&$file, $errorStyle) {
            $attributes = $error['attributes'];
            $row = WriterEntityFactory::createRow();
            $row->addCell(WriterEntityFactory::createCell($error['error'], $errorStyle));
            foreach ($this->tableHeaders as $header) {
                $cell = WriterEntityFactory::createCell($attributes[$header] ?? '');
                $row->addCell($cell);
            }
            $file->addRow($row);
        });
        $file->close();
    }

    public function getErrorFileName()
    {
        return $this->errorFile['name'];
    }

    public function getErrorFilePath()
    {
        return $this->errorFile['path'];
    }

    public function getErrorFileUrl()
    {
        return $this->errorFile['url'];
    }
}
