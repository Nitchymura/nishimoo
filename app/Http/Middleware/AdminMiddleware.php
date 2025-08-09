<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //check if user is logged in, and an admin
        if(Auth::check() && Auth::user()->role_id == User::ADMIN_ROLE_ID){        
            return $next($request); //allow user to see the page
        }else{
            //if not admin, redirect to home
            return redirect()->route('home');
        }
    }
}
