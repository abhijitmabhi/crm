<?php

namespace LocalheroPortal\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RerollToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if(empty($user)){
            return $next($request);
        }
        if(empty($user->api_token)){
            $user->setNewApiToken();
        }

        $response = $next($request);
        $response->header('X-Frame-Options', 'ALLOW FROM *');
        return $response;
        //return $next($request);
    }
}