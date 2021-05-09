<?php

namespace LocalheroPortal\Core\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use LocalheroPortal\Models\Callagent;
use LocalheroPortal\Models\Expert;
use LocalheroPortal\Callcenter\Policies\AgentPolicy;
use LocalheroPortal\Callcenter\Policies\ExpertPolicy;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\Role;
use LocalheroPortal\Core\Policies\RolePolicy;
use LocalheroPortal\Core\Policies\UserPolicy;
use LocalheroPortal\Models\User\User;
use LocalheroPortal\Models\LLI\Company;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class      => UserPolicy::class,
        Role::class      => RolePolicy::class,
        Expert::class    => ExpertPolicy::class,
        Callagent::class => AgentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
        Gate::before(function ($user) {
            if ($user->hasRole('admin')) {
                return true;
            };
        });

        Gate::define('view-experts', function ($user) {
            if ($user->hasRole(RoleType::CALL_CENTER_AGENT)) {
                return true;
            }
            return $user == Request::route()->parameter('expert');
        });

        Gate::define('view-map', function ($user) {
            return !($user->hasRole(RoleType::CUSTOMER) || $user->hasOnlyRole(RoleType::FIX_LEADS));
        });

        Gate::define('supervise-callcenter', function ($user) {
            return $user->hasRole(RoleType::CALL_CENTER_SUPERVISOR);
        });

        Gate::define('manage-company', function (User $user) {
            /**
             * @var Company $company
             */
            $company = Request::route()->parameter('company');
            if ($user->hasRole(RoleType::LLI_MANAGER)) {
                return true;
            }
            if (!empty(Request::route()->parameter('company'))) {
                if (is_object($company)) {
                    return $user->company == $company;
                }
                return $user->company->id == intval($company);
            }
            return false;
        });

        Gate::define('generate-leads', function (User $user) {
            $allowed = [RoleType::EXPERT, RoleType::CALL_CENTER_SUPERVISOR, RoleType::CALL_CENTER_AGENT];
            return $user->roles->contains(function (Role $role) use ($allowed) {
                return in_array($role->name, $allowed);
            });
        });

        Gate::define('fix-leads', function (User $user) {
            $allowed = [RoleType::FIX_LEADS,RoleType::EXPERT, RoleType::CALL_CENTER_AGENT, RoleType::CALL_CENTER_SUPERVISOR];
            return $user->roles->contains(function (Role $role) use ($allowed) {
                return in_array($role->name, $allowed);
            });
        });

        Gate::define('review-sales', function (User $user) {
            $allowed = [RoleType::FINANCE];
            return $user->roles->contains(function (Role $role) use ($allowed) {
                return in_array($role->name, $allowed);
            });
        });
    }
}
