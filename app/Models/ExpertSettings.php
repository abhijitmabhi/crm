<?php

namespace LocalheroPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class RadiusExpert
 * @package LocalheroPortal\Models
 * @property int $id
 * @property int $user_id
 * @property array $coordinates
 * @property int $radius
 * @property array $area
 * @property array $categories
 * @property array $excluded_categories
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ExpertSettings extends Model
{
    protected $casts = [
        'coordinates' => 'array',
        'categories' => 'array',
        'area' => 'array',
        'excluded_categories' => 'array',
    ];

    public static function getDefaultExpertSettings()
    {
        $expertSettings = new ExpertSettings();
        $expertSettings->coordinates = ['lat' => 0, 'long' => 0];
        $expertSettings->radius = 0;
        $expertSettings->categories = [];
        $expertSettings->excluded_categories = [];
        return $expertSettings;
    }
}
