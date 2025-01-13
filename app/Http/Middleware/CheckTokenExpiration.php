<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        $refreshToken = $request->cookie('refreshToken');
        $refreshTokenRecord = PersonalAccessToken::findToken($refreshToken);
        
        if (!$refreshTokenRecord) {
            return response()->json(['message' => 'Refresh token expired or invalid'], 401);
        }
    
        $user = $refreshTokenRecord->tokenable;
    
        if (Carbon::now('Asia/Manila')->greaterThan($refreshTokenRecord->expires_at)) {
            return response()->json(['message' => 'Refresh token expired'], 401);
        }
    
        $accessToken = $request->cookie('accessToken');
        $accessTokenRecord = PersonalAccessToken::findToken($accessToken);
    
        if (!$accessTokenRecord) {
            return response()->json(['message' => 'Access token not found'], 404);
        }

        Log::info('Access token: ' . $accessTokenRecord);
    
        $accessTokenExpiry = Carbon::parse($accessTokenRecord->expires_at)->toDateTimeString();

        // Check if the access token is expired
        if (Carbon::now('Asia/Manila')->greaterThan($accessTokenExpiry)) {
            
            $accessTokenRecord->delete();
            $refreshTokenRecord->delete();
    
            $newAccessToken = $user->createToken('access-token', ['*'], Carbon::now('Asia/Manila')->addMinute(1))->plainTextToken;
            $newRefreshToken = $user->createToken('refresh-token', ['*'], now()->addDays(7))->plainTextToken;
    
            return response()->json([
                'message' => 'Access token refreshed',
                'accessToken' => $newAccessToken,
                'refreshToken' => $newRefreshToken,
            ])
            ->cookie('accessToken', $newAccessToken, 70, '/', null, true, true, false, 'Lax')
            ->cookie('refreshToken', $newRefreshToken, 10080, '/', null, true, true, false, 'Lax');
        }

        return $next($request);
    }
}


