<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ]
        ])->cookie('auth_token', $token, 60, null, null, false, true);
    }

    public function register(Request $request) {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'password' => 'required|string|min:8|confirmed'
        // ], [], [], true);
        
        $data = $request->only(['name', 'email', 'password']); 
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $data,
            'token' => $token
        ])->cookie('auth_token', $token, 60, null, null, false, true);
    }

    public function helloWorld(Request $request) {
        $user = auth()->user(); 
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
    
        return response()->json([
            'message' => 'Hello World',
            'token' => $request->bearerToken(), 
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ]
        ]);
    }
    
}
