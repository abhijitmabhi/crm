<?php

namespace LocalheroPortal\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sentry\State\Scope;
use function Sentry\configureScope;

class SentryMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (app()->bound('sentry')) {

            if (auth()->check()) {
                configureScope(function (Scope $scope): void {
                    $scope->setUser(['id' => auth()->user()->id]);
                });
            }

            // Add tags context
//            $sentry = app('sentry');
            // $sentry->tags_context(['foo' => 'bar']);
        }

        return $next($request);
    }
}
