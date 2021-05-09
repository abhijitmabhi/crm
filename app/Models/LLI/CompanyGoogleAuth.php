<?php

namespace LocalheroPortal\Models\LLI;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * LocalheroPortal\Models\LLI\CompanyGoogleAuth
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CompanyGoogleAuth newModelQuery()
 * @method static Builder|CompanyGoogleAuth newQuery()
 * @method static Builder|CompanyGoogleAuth query()
 * @method static Builder|CompanyGoogleAuth whereAccessToken($value)
 * @method static Builder|CompanyGoogleAuth whereCompanyId($value)
 * @method static Builder|CompanyGoogleAuth whereCreatedAt($value)
 * @method static Builder|CompanyGoogleAuth whereId($value)
 * @method static Builder|CompanyGoogleAuth whereRefreshToken($value)
 * @method static Builder|CompanyGoogleAuth whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CompanyGoogleAuth extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        if (strtotime($this->updated_at) < time() - Config::get('google_api.expire_time')) {
            return $this->refreshAccessToken();
        }
        return $this->access_token;
    }

    /**
     * @return bool
     */
    public function isRefreshTokenValid(): bool
    {
        $accessToken = $this->refreshAccessToken();
        if (!empty($accessToken)) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function refreshAccessToken()
    {
        $client = resolve('Google_Client');
        if (empty($this->refresh_token)) {
            return;
        }
        $accessToken = $client->refreshToken($this->refresh_token);
        if (!empty($accessToken['access_token'])) {
            $this->access_token = $accessToken['access_token'];
            $this->save();
            return $accessToken['access_token'];
        }
    }
}
