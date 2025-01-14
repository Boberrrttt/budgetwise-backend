<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon; 

class AuthController extends Controller
{
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ], [], [], true);
        
        $data = $request->only(['name', 'email', 'password']); 
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $accessToken = $user->createToken('access-token', ['*'], Carbon::now('Asia/Manila')->addMinute(60))->plainTextToken;
        // $refreshToken = $user->createToken('refresh-token', ['*'], Carbon::now('Asia/Manila')->addDays(7))->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $data,
            // 'accessToken' => $accessToken   
        ])
        ->cookie('accessToken', $accessToken, 70, null, null, true, false);
        // ->cookie('refreshToken', $refreshToken, 10080, '/', null, true, true, false, 'Lax');
    }
    
    public function login(Request $request) {
        $credentials = $request->validate([ 
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('access-token', ['*'], Carbon::now('Asia/Manila')->addMinute(60))->plainTextToken;
        // $refreshToken = $user->createToken('refresh-token', ['*'], Carbon::now('Asia/Manila')->addDays(7))->plainTextToken;
        
        return response()->json([
            'message' => 'Login successful',
            // 'accessToken' => $accessToken
        ])
        ->cookie('accessToken', $accessToken, 70, null, null, true, false);
        // ->cookie('refreshToken', $refreshToken, 10080, null, null, false, true);

    }
}
