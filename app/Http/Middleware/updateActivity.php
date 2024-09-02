<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class updateActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $sessionId = session()->getId();

            // Perbarui last_activity di tabel sessions
            Session::where('id', $sessionId)
              ->update(['last_activity' => now()->timestamp]);
        }
        return $next($request);
    }
}