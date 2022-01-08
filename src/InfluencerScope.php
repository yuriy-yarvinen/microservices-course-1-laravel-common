<?php

namespace Microservices;

use Closure;
use Microservices\UserService;
use Illuminate\Auth\AuthenticationException;

class InfluencerScope
{

    public function handle($request, Closure $next)
    {
        if((new UserService())->isInfluencer()){

            return $next($request);
        }

        throw new AuthenticationException;
    }
}
