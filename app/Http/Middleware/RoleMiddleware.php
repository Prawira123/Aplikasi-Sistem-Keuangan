<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {

        $userID = Auth::user()->id;
        $user = User::find($userID);

        $request->session()->put('role', $user->role);
        $request->session()->put('user_id', $user->id);

        if(!in_array($user->role, $roles)){
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
