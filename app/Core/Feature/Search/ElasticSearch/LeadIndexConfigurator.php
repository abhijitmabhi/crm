<?php

namespace LocalheroPortal\Core\Feature\Search\ElasticSearch;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class LeadIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = IndexConfig::settings;
}