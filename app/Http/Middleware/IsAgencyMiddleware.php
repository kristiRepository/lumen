<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class IsAgencyMiddleware
{
    use ApiResponser;
  
     
    public function __construct()
    {
        
    }

  
    public function handle($request, Closure $next)
    {
        
        if (Auth::user()->role == 'agency') {
            return $next($request);
        }else{
            return $this->errorResponse('User not authorized for this action',401);
        }

        
    }
}