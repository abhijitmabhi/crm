<?php

namespace LocalheroPortal\LLI\Services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use libphonenumber\NumberParseException;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\Batch;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\User;

class CompanyImporter
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var Batch
     */
    private $batch;

    /**
     * The User who started the import
     * @var User
     */
    private $authUser;

    private $companies = [];

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
        'name',
        'contact_name',
        'street',
        'zip',
        'city',
        'phone',
        'phone_mobile',
        'email',
        'url',
        'user_email',
        'user_password',
        'representative',
    ];

    private $required = ['name', 'email', 'user_password'];

    /**
     * @param string $inputFile
     */
    public function batchImport($inputFile)
    {
        $this->input = $inputFile;
        $this->parseFile();
        $this->commentCompany();
    }

    public function __construct(User $authUser)
    {
        $this->authUser = $authUser;
        $this->batch = new Batch(['type' => 'company']);
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
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
        return count($this->errors) > 0;
    }

    /**
     * @param array $attributes
     */
    public function importCompany($attributes)
    {
        if (Company::where('email', 'like', $attributes['email'])->exists()) {
            $this->errors[] = '<strong>Fehler beim Import</strong> ' . $attributes['name'] . ': Doppelter Eintrag erkannt';
            return false;
        }

        $user_data = [
            'password' => $attributes['user_password'],
            'email'    => $attributes['user_email'] ?? $attributes['email'],
        ];
        unset($attributes['user_password']);
        unset($attributes['user_email']);

        $lead = Lead::where(function (Builder $query) use ($attributes) {
            $query->where('phone1', $attributes['phone'])
            ->orWhere('phone2', $attributes['phone'])
            ->orWhere('email', $attributes['email']);
        })->first();

        DB::beginTransaction();
        try {
            $user = User::firstOrCreate(
                ['email' => $user_data['email']],
                [
                    'name'              => $attributes['name'],
                    'email_verified_at' => now(),
                    'password'          => Hash::make($user_data['password'])
                ]
            );

            $attributes['user_id'] = $user->id;
            if ($lead) {
                $lead->status = LeadState::CLOSED;
                $attributes['lead_id'] = $lead->id;
            }

            $company = Company::create($attributes);
            DB::commit();
            $this->companies[] = $company->id;
            $this->successes++;
        } catch (Exception $e) {
            $message = $e->getMessage();
            if (Str::contains($message, "doesn't have a default value")) {
                $field = [];
                $company->errror = preg_match("/Field \'(.*)\' doesn't/", $message, $field);
                $company->error = '<strong>Fehler beim Import</strong> Leeres Feld: ' . __("attributes.$field[1]");
                $this->errors[] = $attributes['name'] . ': ' . __("attributes.$field[1]");
            } else {
                $company->error = $message;
                $this->errors[] = $message;
            }
            DB::rollBack();
        }
    }

    /**
     * @param  array   $attributes
     * @return mixed
     */
    private function formatAttributes($attributes)
    {
        /**
         * Zielformat:
         * name,
         * ansprechpartner
         * str
         * zip
         * city
         * phone
         * mobile
         * email
         * url
         * email (user)
         * password
         * vertreter
         */
        $attributes = array_combine($this->tableHeaders, $attributes);
        try {
            $attributes['phone'] = PhoneUtil::formatPhoneNumber($attributes['phone']);
        } catch (NumberParseException $e) {
            $this->errors[] = '<strong>Fehler beim Import</strong> ' . $attributes['name'] . ': ' . $e->getMessage();
            return false;
        }
        return $this->removeEmptyFields($attributes);
    }

    private function parseFile()
    {
        /**
         * Ausgangsformat:
         * ------------
         * name,
         * ansprechpartner
         * str
         * zip
         * city
         * phone
         * mobile
         * email
         * url
         * email (user)
         * password
         * vertreter
         * ------------ nur bis hierher relevant
         * NULL
         * LLI-Auftragsnr
         * Autragsdatum
         * Auftragswert
         * Status
         * NULL
         * LSB-Auftragsnr
         * LSB-Auftragsdatum
         * LSB-Auftragsbeginn
         * LSB-Auftragsende
         * Monatsbudget
         * Status
         * NULL
         * LPP-Auftragsnr
         * LPP-Datum
         * LPP-Wert
         * LPP-ServicePauschale
         * Status
         */
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

                $cells = $row->getCells();
                foreach ($this->tableHeaders as $index => $header) {
                    if (!empty($cells[$index])) {
                        $cell = $cells[$index];
                        if ('phone' === $header) {
                            $nr = (string)$cell->getValue();
                            $rowItems[$header] = preg_replace('/^(49)/', '+49', $nr);
                        } else {
                            $rowItems[$header] = $cell->getValue();
                        }
                    } else {
                        $rowItems[$header] = false;
                    }
                }
                $missingAttrs = array_filter($this->tableHeaders, function ($header) use ($rowItems) {
                    return false == $rowItems[$header];
                });
                $missingReqAttrs = count($this->required) !== count(array_diff($this->required, $missingAttrs));
                if ($missingReqAttrs) {
                    $missingAttrs = array_map(function ($header) {
                        return __("attributes.$header");
                    }, $missingAttrs);
                    $this->errors[] = "<strong>Fehlerhafter Datensatz</strong> Fehlende Felder in Zeile $rowNr: " . implode(', ', $missingAttrs);
                } else {
                    $attributes = $this->formatAttributes($rowItems);
                    if ($attributes) {
                        $this->importCompany($attributes);
                    }
                }
            }
        }
        $this->batch->items = $this->companies;
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

    private function commentCompany()
    {
        foreach ($this->companies as $companyId) {
            Comment::create([
                'reason'           => CommentReason::CREATED,
                'body'             => "Kunde wurde von {$this->authUser->name} importiert.",
                'user_id'          => $this->authUser->id,
                'commentable_type' => 'company',
                'commentable_id'   => $companyId,
                'date'             => now('Europe/Berlin'),
            ]);
        }
    }
}
