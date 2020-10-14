<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class IsAgencyMiddleware
{
    use ApiResponser;


    /**
     * Undocumented function
     */
    public function __construct()
    {
    }


    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param Closure $next
     * @return void
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()->role == 'agency') {
            return $next($request);
        } else {
            return $this->errorResponse('User not authorized for this action', 401);
        }
    }
}
