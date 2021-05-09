<?php


namespace LocalheroPortal\Core\Feature\Search;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use LocalheroPortal\Core\Repository\CompanyRepository;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Core\Util\PhoneUtil;

class SearchService
{

    public function searchWithSql(string $searchTerm, string $phone, int $limit)
    {
        $searchTerm = $this->formatSearchTerm($searchTerm);
        $phone = $this->formatPhone($phone);

        $leadRepo = new LeadRepository();
        $leads = $leadRepo->search($searchTerm, $phone);
        $companyRepo = new CompanyRepository();
        $companies = $companyRepo->search($searchTerm, $phone, $searchTerm);
        $locationRepo = new LocationRepository();
        $locations = $locationRepo->search($searchTerm, $phone);

        return ['leads' => $leads, 'companies' => $companies, 'locations' => $locations];
    }

    private function formatSearchTerm(string $searchTerm): string
    {
        $searchTerm = str_replace(' ', '%', $searchTerm);
        if ($searchTerm != '') {
            $searchTerm = '%'.$searchTerm.'%';
        }
        return $searchTerm;
    }

    private function formatPhone(string $phone): string
    {
        if (PhoneUtil::isValidPhoneNumber($phone)) {
            $phone = PhoneUtil::formatPhoneNumber($phone);
        } else {
            if ($phone != '') {
                $phone = '%'.$phone.'%';
            }
        }
        return $phone;
    }

    public function searchLocations(string $searchTerm, string $phone, int $limit)
    {
        $searchTerm = $this->formatSearchTerm($searchTerm);
        $phone = $this->formatPhone($phone);

        $locationRepo = new LocationRepository();
        return $locationRepo->search($searchTerm, $phone);
    }

    public function searchCompanies(string $searchTerm, string $phone, string $email, int $limit)
    {
        $searchTerm = $this->formatSearchTerm($searchTerm);
        $phone = $this->formatPhone($phone);

        $companyRepo = new CompanyRepository();
        return $companyRepo->search($searchTerm, $phone, $email);
    }

    public function searchLeads(string $searchTerm, string $phone, int $limit)
    {
        $searchTerm = $this->formatSearchTerm($searchTerm);
        $phone = $this->formatPhone($phone);

        $leadRepo = new LeadRepository();
        return $leadRepo->search($searchTerm, $phone);
    }

    public function searchWithElastic($searchTerm, $phone, $limit): Collection
    {
        $results = new Collection();

        $query = [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'match' => [
                                'name.autocomplete' => [
                                    'query' => $searchTerm,
                                    'fuzziness' => 2
                                ]
                            ],
                        ],
                        [
                            'match' => [
                                'phone.autocomplete' => [
                                    'query' => $searchTerm
                                ]
                            ],
                        ],
                    ]
                ],
            ],

        ];

        $leads = Http::post("localhost:9200/lead/_search", $query)->json();
        $companies = Http::post("localhost:9200/company/_search", $query)->json();

        $leadHits = Arr::get($leads, 'hits.hits', []);
        $companyHits = Arr::get($companies, 'hits.hits', []);
        $locationHits = new Collection();

        $hits = array_merge($leadHits, $companyHits, $locationHits);

        foreach ($hits as $hit) {
            $results->push([
                'id' => $hit['_id'],
                'name' => $hit['_source']['name'],
                'tel' => $hit['_source']['phone'],
                'score' => $hit['_score']
            ]);
        }
        return $results->sortByDesc('score')->take($limit);
    }

}