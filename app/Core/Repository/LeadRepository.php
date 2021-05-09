<?php


namespace LocalheroPortal\Core\Repository;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Core\Util\UrlUtil;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\LeadStateTag;

class LeadRepository
{

    public function getAllSimilarLeads($email, $website, $phone, $mobile)
    {
        if (!$email && !$website && !$phone && !$mobile) {
            return collect();
        }
        return $this->getSimilarLeadQuery($email, $website, $phone, $mobile)->get();
    }

    public function getFirstSimilarLead($email, $website, $phone, $mobile)
    {
        if (!$email && !$website && !$phone && !$mobile) {
            return null;
        }
        return $this->getSimilarLeadQuery($email, $website, $phone, $mobile)->first();
    }

    private function getSimilarLeadQuery($email, $website, $phone, $mobile)
    {
        $query = Lead::query();
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
                ->orWhere('phone1', $phone)
                ->orWhere('phone2', $phone);
        }
        if ($mobile) {
            $query = $query
                ->orWhere('phone1', $mobile)
                ->orWhere('phone2', $mobile);
        }
        return $query;
    }

    public function getAcuteRecalls($user)
    {
        $query = Lead::withTrashed()
            ->where('agent_id', $user->id)
            ->whereHas('expert')
            ->acuteRecalls()
            ->callable();
        return $this->filterByIndustryTime($query);
    }

    public function getRecalls($user)
    {
        $query = Lead::withTrashed()
            ->where('agent_id', $user->id)
            ->whereHas('expert')
            ->state(LeadState::RECALL)
            ->callable();
        return $this->filterByIndustryTime($query);
    }

    public function getLeads($expert, $states)
    {
        $query = $expert->leads()
            ->whereNotNull('contact_name')
            ->where('in_pipeline', 1)
            ->where('blocked', '=', 0)
            ->whereIn('status', $states)
            ->callable()
            ->orderBy('created_at', 'desc');
        $expertSettings = $expert->expertSettings()->first();
        $query = $this->filterByExpertSettings($query, $expertSettings);
        return $this->filterByIndustryTime($query);
    }

    public function filterByExpertSettings($query, $expertSettings)
    {
        if ($expertSettings) {
            $query = $this->filterByExpertLocation($query, $expertSettings);
            if ($expertSettings->categories) {
                $query = $query->whereIn('category', $expertSettings->categories);
            }
            if ($expertSettings->excluded_categories) {
                $query = $query->whereNotIn('category', $expertSettings->excluded_categories);
            }
        }
        return $query;
    }

    public function filterByExpertLocation($query, $expertSettings)
    {
        if ($expertSettings) {
            $lat = $expertSettings->coordinates['lat'];
            $long = $expertSettings->coordinates['long'];
            if ($lat > 0 && $long > 0) {
                // https://developers.google.com/maps/solutions/store-locator/clothing-store-locator#findnearsql
                $query = $query->whereRaw(
                    "(
                        6371 * acos(
                            cos( radians($lat) ) 
                            * cos( radians( ST_Y(`geo_coordinates`) ) ) 
                            * cos( radians( ST_X(`geo_coordinates`) ) - radians($long) )
                            + sin( radians($lat) ) * sin( radians( ST_Y(`geo_coordinates`) ) )
                        ) 
                    ) < $expertSettings->radius"
                );
            }
        }
        return $query;
    }

    public function filterByIndustryTime($query)
    {
        $time = now('Europe/Berlin');
        $blacklist = $this->getCategoryBlackList($time);
        $query->whereNotIn('category', $blacklist);
        return $query;
    }

    public function getCategoryBlackList($time)
    {
        $blacklist = [];
        if ($time->hour < 10) {
            $addition = [
                'Autohaus',
                'Optiker',
                'Hörgeräte',
                'Küchenstudio',
                'Möbelhaus',
                'Brautmode',
                'Gartencenter',
                'Bettenhaus',
                'Fahrradhändler',
                'Motorradhändler'
            ];
            $blacklist = array_merge($blacklist, $addition);
        }

        if ($time->isDayOfWeek('wednesday') && $time->hour >= 12) {
            $addition = [
                'Schönheitschirurg',
                'Physiotherapeut',
                'Zahnarzt',
                'Orthopäde',
                'Augenarzt',
                'Arzt'
            ];
            $blacklist = array_merge($blacklist, $addition);
        }

        return $blacklist;
    }

    public function getFollowUpLeads($expert, $states)
    {
        $oneWeekAgo = now('Europe/Berlin')->subDays(7);
        $query = $expert->leads()
            ->whereIn('status', $states)
            ->whereHas('allCalendarEvents')
            ->whereDoesntHave('allCalendarEvents', function (Builder $query) use ($oneWeekAgo) {
                return $query->where('event_end', '>', $oneWeekAgo);
            })
            ->whereJsonDoesntContain('states', LeadStateTag::FOLLOW_UP_ACCEPTED)
            ->whereJsonDoesntContain('states', LeadStateTag::FOLLOW_UP_REJECTED)
            ->whereJsonDoesntContain('states', LeadStateTag::FOLLOW_UP_SKIPPED)
            ->orderBy('updated_at', 'desc');
        return $this->filterByIndustryTime($query);
    }

    public function countByStatusGroupByCategories()
    {
        return Lead::query()
            ->select($this->getCountByStatusSelectStatement())
            ->groupBy('category')
            ->get();
    }

    public function countByStatusGroupByExpertIds()
    {
        return Lead::query()
            ->select($this->getCountByStatusSelectStatement())
            ->groupBy('expert_id')
            ->get();
    }

    private function getCountByStatusSelectStatement()
    {
        return DB::raw(
            "category,
                expert_id,
                COUNT(*) AS totalCount,
                COUNT(CASE status WHEN 5 THEN 1 ELSE null END) AS appointmentCount,
                COUNT(CASE status WHEN 1 THEN 1 ELSE null END) AS openCount,
                COUNT(CASE WHEN status = 3 THEN 1 ELSE null END) AS recallCount,
                COUNT(CASE WHEN status = 2 THEN 1 ELSE null END) AS notReachedCount,
                COUNT(CASE WHEN status = 8 THEN 1 ELSE null END) AS invalidCount,
                COUNT(CASE WHEN status = 6 THEN 1 ELSE null END) AS blacklistCount,
                COUNT(CASE WHEN status = 7 THEN 1 ELSE null END) AS closedCount,
                COUNT(CASE WHEN status = 9 THEN 1 ELSE null END) AS tooManyTriesCount,
                COUNT(CASE WHEN status = 4 THEN 1 ELSE null END) AS noInterestCount
                "
        );
    }

    public function countByStatusGroupByCategoryForExpert($expertId)
    {
        return Lead::query()
            ->select($this->getCountByStatusSelectStatement())
            ->groupBy('category')
            ->where('expert_id', '=', $expertId)
            ->get();
    }

    public function countPipelineByStatusForExpert($expert)
    {
        $leadStates = [LeadState::OPEN, LeadState::INVALID, LeadState::NO_INTEREST, LeadState::NOT_REACHED,
            LeadState::RECALL, LeadState::APPOINTMENT, LeadState::BLACKLIST];
        return $this->getLeads($expert, $leadStates)
            ->select($this->getCountByStatusSelectStatement())
            ->get();
    }

    public function getAllCategoriesAsObject()
    {
        return Lead::query()->distinct()->get(['category']);
    }

    public function getAllCategoriesAsString()
    {
        return Lead::query()->whereNotNull('category')->groupBy('category')->pluck('category');
    }

    public function search(string $searchTerm, string $phone)
    {
        return Lead::query()->where('company_name', 'like', $searchTerm)
            ->orWhere('phone1', 'like', $phone)
            ->select(['id', 'company_name', 'zip', 'city', 'street', 'phone1'])
            ->get();
    }
}
