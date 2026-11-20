<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->role_id == User::ADMIN_ROLE_ID){
            // first check if the user is LOGGED IN && if the AUTH USER'S role ID is equals to ADMIN ROLE ID(1)
            return $next($request);          // proceeds to next request
        }
        return redirect()->route('index');   // if false, return home page
    }
}
