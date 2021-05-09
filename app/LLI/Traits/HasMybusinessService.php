<?php

namespace LocalheroPortal\LLI\Traits;

use Exception;
use Google_Service_MyBusiness;

/**
 * Needs a company Object to function
 */
trait HasMybusinessService
{
    /**
     * @var Google_Service_MyBusiness
     */
    protected $googleMyBusinessService;

    public function init_google_service_mybusiness()
    {
        $client = resolve('Google_Client');
        $token = $this->company->googleAuth;
        if (!is_object($token) && !$token->isRefreshTokenValid()) {
            throw (new Exception('Could not setup client.'));
        }
        $client->setAccessToken($token->getAccessToken());
        $this->googleMyBusinessService = new Google_Service_MyBusiness($client);
    }
}
