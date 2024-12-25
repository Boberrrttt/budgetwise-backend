<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()->currentAccessToken();
        $expirationHours = 1;

        if($token && $token->created_at->lt(Carbon::now()->subHours($expirationHours))) {
            $token->delete();
            return response()->json(['message' => 'Token expired'], 401);
        }

        return $next($request);
    }
}
