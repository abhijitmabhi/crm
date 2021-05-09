<?php

namespace LocalheroPortal\LLI\Jobs;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Google_Service_MyBusiness_BasicMetricsRequest;
use Google_Service_MyBusiness_DimensionalMetricValue;
use Google_Service_MyBusiness_LocationMetrics;
use Google_Service_MyBusiness_MetricRequest;
use Google_Service_MyBusiness_MetricValue;
use Google_Service_MyBusiness_ReportLocationInsightsRequest;
use Google_Service_MyBusiness_TimeRange;
use Illuminate\Support\Str;
use LocalheroPortal\Core\Jobs\BaseJob;
use LocalheroPortal\LLI\Feature\Location\LocationValidationUseCase;
use LocalheroPortal\LLI\Traits\HasMybusinessService;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationInsight;
use LocalheroPortal\Models\LLI\LocationState;

class GetMetrics extends BaseJob
{
    use HasMybusinessService;

    protected Company $company;
    protected Location $location;
    protected ?Google_Service_MyBusiness_ReportLocationInsightsRequest $request = null;
    protected ?Google_Service_MyBusiness_TimeRange $timeRange = null;
    protected ?Google_Service_MyBusiness_BasicMetricsRequest $basicRequest = null;

    protected $metrics = [
        'QUERIES_DIRECT',
        'QUERIES_INDIRECT',
        'QUERIES_CHAIN',
        'ACTIONS_WEBSITE',
        'ACTIONS_DRIVING_DIRECTIONS',
        'ACTIONS_PHONE'
    ];

    public function __construct(Company $company, Location $location)
    {
        $this->company = $company;
        $this->location = $location;
    }

    public function handle(): void
    {
        if (!config('api_settings.lli_jobs_enabled')) {
            return;
        }
        $this->initRequest();
        $insightsArray = [];
        $insights = $this->googleMyBusinessService
            ->accounts_locations
            ->reportInsights($this->getAccountIdentifier(), $this->request);

        $locationRepo = new LocationRepository();
        foreach ($insights->getLocationMetrics() as $locationMetric) {
            $insightLocation = $locationRepo->getFirstByGoogleBusinessKey($locationMetric->getLocationName());
            $locationId = $insightLocation->id;

            foreach ($locationMetric->getMetricValues() as $metric) {
                /**
                 * @var Google_Service_MyBusiness_MetricValue $metric
                 */
                $type = $metric->getMetric();

                foreach ($metric->getDimensionalValues() as $value) {
                    /**
                     * @var Google_Service_MyBusiness_DimensionalMetricValue $value
                     */
                    if (!$value->offsetExists('value')) {
                        continue;
                    }

                    $insightsArray[] = [
                        'location_id' => $locationId,
                        'type' => $type,
                        'value' => $value->getValue(),
                        'date' => Carbon::parse($value->timeDimension->timeRange->startTime)->setHours(23)
                    ];
                }
            }
        }
        LocationInsight::insertOrUpdate($insightsArray);
        $useCase = new LocationValidationUseCase($this->location);
        $useCase->onStatisticsChanged();
        $this->location->save();
    }

    private function initRequest()
    {
        $this->init_google_service_mybusiness();
        $this->request = new Google_Service_MyBusiness_ReportLocationInsightsRequest();
        $this->setTimeRange();
        $this->setBasicRequest();
        $this->request->setLocationNames($this->getLocationIdentifiers());
    }

    private function getAccountIdentifier()
    {
        $locationName = $this->company->locations()->first()->google_business_key;
        return Str::before($locationName, '/locations');
    }

    private function setTimeRange()
    {
        $this->timeRange = new Google_Service_MyBusiness_TimeRange();
        $now = CarbonImmutable::now();
        $startDate = $now->subMonths(18);
        $startDate = $startDate->addDays(1);
        // Google My Business provides the data three days after it was created
        $endDate = $now->subDays(3);
        $this->timeRange->setStartTime($startDate->toRfc3339String());
        $this->timeRange->setEndTime($endDate->toRfc3339String());
    }

    private function setBasicRequest()
    {
        $this->basicRequest = new Google_Service_MyBusiness_BasicMetricsRequest();
        $this->basicRequest->setMetricRequests($this->geMetricsRequests());
        $this->basicRequest->setTimeRange($this->timeRange);
        $this->request->setBasicRequest($this->basicRequest);
    }

    private function geMetricsRequests()
    {
        $requests = [];
        foreach ($this->metrics as $metric) {
            $metricRequest = new Google_Service_MyBusiness_MetricRequest();
            $metricRequest->setMetric($metric);
            $metricRequest->setOptions('AGGREGATED_DAILY');
            $requests[] = $metricRequest;
        }
        return $requests;
    }

    private function getLocationIdentifiers()
    {
        $identifiers = [];
        $identifiers[] = $this->location->google_business_key;
        return $identifiers;
    }
}
