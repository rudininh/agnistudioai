
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// Use the Eloquent user model instead of the domain entity for auth

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request )
    {
        ->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

         = User::create([
            'first_name' => ->first_name,
            'last_name' => ->last_name,
            'email' => ->email,
            'password' => Hash::make(->password),
        ]);

         = ->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => ,
            'token' => 
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(Request )
    {
        ->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

         = User::where('email', ->email)->first();

        if (! || !Hash::check(->password, ->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

         = ->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => ,
            'token' => 
        ], 200);
    }

    /**
     * Logout user.
     */
    public function logout(Request )
    {
        ->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }

    /**
     * Refresh token.
     */
    public function refresh(Request )
    {
         = ->user();
         = ->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => ,
            'token' => 
        ], 200);
    }

    /**
     * Get authenticated user.
     */
    public function user(Request )
    {
        return response()->json(->user(), 200);
    }
}

