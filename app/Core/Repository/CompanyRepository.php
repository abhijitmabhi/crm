<?php


namespace LocalheroPortal\Core\Repository;

use LocalheroPortal\Models\LLI\Company;

class CompanyRepository
{

    public function getWithoutLocations() {
        return Company::doesntHave('locations')->get();
    }

    public function get(string $searchTerm = null, int $per_page = null)
    {
        $query = Company::query()->when(!empty($searchTerm), function($query) use ($searchTerm) {
            $searchTerm = str_replace(' ', '%', $searchTerm);
            $searchTerm = '%'.$searchTerm.'%';
            return $query->where('name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm);
        });
        return $per_page ? $query->paginate($per_page) : $query->get();
    }

    //TODO: refactor with upper get function
    public function search(string $searchTerm, string $phone, string $email)
    {
        return Company::query()->where('name', 'like', $searchTerm)
            ->orWhere('phone', 'like', $phone)
            ->orWhere('email', 'like', $email)
            ->select(['id', 'name', 'zip', 'city', 'street', 'phone'])
            ->get();
    }

}