<?php

namespace LocalheroPortal\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Jenssegers\Optimus\Optimus;
use LocalheroPortal\Core\Facades\PhoneFormatter;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        parent::boot();

        Route::bind('lead', function ($id) {
            return Lead::withTrashed()->where('id', $id)->first();
        });

        Route::bind('expert_cal', function ($id) {
            /**
             * @var Optimus $optimus
             */
            return User::findOrFail(app(Optimus::class)->decode($id));
        });

        Route::bind('phone_number', function ($number) {
            return Lead::wherePhone1(PhoneFormatter::formatInternational($number))->first();
        });
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapCallcenterRoutes();

        $this->mapCallcenterApiRoutes();

        $this->mapLLIRoutes();

        $this->mapLLIApiRoutes();

        $this->mapBusinessRoutes();

        $this->mapBusinessApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->name('api.')
            ->middleware(['api', 'auth:api'])
            ->group(base_path('routes/api.php'));
    }

    protected function mapBusinessApiRoutes()
    {
        Route::prefix('api')
            ->name('api.')
            ->middleware(['api', 'auth:api'])
            ->group(base_path('routes/business/api.php'));
    }

    protected function mapBusinessRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->group(base_path('routes/business/web.php'));
    }

    protected function mapCallcenterApiRoutes()
    {
        Route::prefix('api')
            ->name('api.')
            ->middleware(['api', 'auth:api'])
            ->group(base_path('routes/callcenter/api.php'));
    }

    protected function mapCallcenterRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->group(base_path('routes/callcenter/web.php'));
    }

    protected function mapLLIApiRoutes()
    {
        Route::prefix('api')
            ->name('api.')
            ->middleware(['api', 'auth:api'])
            ->group(base_path('routes/lli/api.php'));
    }

    protected function mapLLIRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->group(base_path('routes/lli/web.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}