<?php

namespace LocalheroPortal\Core\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Optimus\Optimus;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Facades\PhoneFormatter\Formatter;
use LocalheroPortal\Models\User\User;
use LocalheroPortal\Models\LLI\Company;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Carbon::setLocale('de');
        JsonResource::withoutWrapping();
        view()->composer(
            'callcenter.index',
            'LocalheroPortal\Callcenter\Http\ViewComposers\CallcenterComposer'
        );
        Blade::aliasComponent('components.breadcrumbs', 'breadcrumbs');
        Blade::aliasComponent('partials.nav-links', 'navlinks');

        Relation::morphMap([
            'lead' => Lead::class,
            'company' => Company::class,
            'user' => User::class,
        ]);

        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(Optimus::class, function ($app) {
            return new Optimus(1383432049, 1178165649);
        });
        $this->app->bind(Formatter::class, function(){
            return new Formatter();
        });
    }
}
