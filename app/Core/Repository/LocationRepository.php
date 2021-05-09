<?php


namespace LocalheroPortal\Core\Repository;


use Illuminate\Support\Carbon;
use LocalheroPortal\Core\Util\UrlUtil;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;

class LocationRepository
{

    public function getById($id)
    {
        return Location::whereId($id)->first();
    }

    public function getByCompanyId($companyId)
    {
        return Location::whereCompanyId($companyId)->first();
    }

    public function getFirstByGoogleBusinessKey(string $key)
    {
        return Location::whereGoogleBusinessKey($key)->first();
    }

    public function getFirstByAddress($postCode, $city, $street)
    {
        return Location::whereAddress($street)
            ->where('postcode', '=', $postCode)
            ->where('city', '=', $city)
            ->first();
    }

    public function getFirstSimilarLocation($name, $email, $website, $phone, $mobile)
    {
        if (!$name && !$email && !$website && !$phone && !$mobile) {
            return null;
        }
        return $this->getSimilarLocationQuery($name, $email, $website, $phone, $mobile)->first();
    }

    private function getSimilarLocationQuery($name, $email, $website, $phone, $mobile)
    {
        $query = Location::query();
        if ($name) {
            $query = $query->orWhere('name', $name);
        }
        if ($email) {
            $query = $query->orWhere('email', $email);
        }
        if ($website) {
            $domain = UrlUtil::getUrlDomain($website);
            $query = $query
                ->orWhere('website', 'like', '%' . $domain . '%');
        }
        if ($phone) {
            $query = $query
                ->orWhere('phone', $phone)
                ->orWhere('mobilephone', $phone);
        }
        if ($mobile) {
            $query = $query
                ->orWhere('phone', $mobile)
                ->orWhere('mobilephone', $mobile);
        }
        return $query;
    }

    public function getLocationNoScrapingResults() {
        return Location::query()
            ->doesntHave('keywordUsageResults')
            ->whereJsonDoesntContain('states', LocationState::HAS_PROBLEM)
            ->first();
    }

    public function getLocationWithScrapingResults() {
        //TODO: is join really necessary?
        return Location::query()
            ->leftJoin('my_business_terms_scraping_results', 'locations.id', '=', 'my_business_terms_scraping_results.location_id')
            ->whereJsonDoesntContain('states', LocationState::HAS_PROBLEM)
            ->where('my_business_terms_scraping_results.fetched_at', '<=', Carbon::today()->subDays(7))
            ->oldest('my_business_terms_scraping_results.fetched_at')
            ->first();
    }

    public function getByState($state)
    {
        return Location::whereJsonContains('states', $state)->get();
    }

    public function getUnfinished() {
        return Location::whereJsonDoesntContain('states', LocationState::ACTIVATED)
            ->with('company.googleAuth')
            ->get();
    }

    public function search(string $searchTerm, string $phone)
    {
        return Location::query()->where('name', 'like', $searchTerm)
            ->orWhere('phone', 'like', $phone)
            ->select(['id', 'name', 'postcode', 'city', 'address', 'phone', 'company_id'])
            ->get();
    }
}