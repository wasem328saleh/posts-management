<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ApiResponderTrait;
use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsActiveAccount
{
    use ApiResponderTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!app()->runningInConsole()) {
            $user = User::where('email',$request['email'])->first();
            if ($user && !$user->is_active)
            {
                return $this->returnError(403,'Account Not Active');
            }
        }
        return $next($request);
    }
}
