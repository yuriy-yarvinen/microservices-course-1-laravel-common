<?php

namespace Microservices;

use Closure;
use Microservices\UserService;
use Illuminate\Auth\AuthenticationException;

class AdminScope
{
    
    public function handle($request, Closure $next)
    {
        if((new UserService())->isAdmin()){

            return $next($request);
        }

        throw new AuthenticationException;

    }
}
