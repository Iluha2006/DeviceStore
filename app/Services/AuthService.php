<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthService
{
    public function attemptLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return false;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return 
        [
            'user' => $user,
            'token' => $token
        ];
    }

    public function getAuthenticatedUser()
    {
        return auth()->user();
    }

    public function respondWithToken($token, $user)
    {
        $cookie = cookie(
            'laravel-sanctum',
            $token,
            config('sanctum.expiration'),
            null,
            null,
            false,
            true,  
            false,
            'Lax'
        );

        return response()->json([
            'user' => $user,
            'token' => $token
        ])->withCookie($cookie);
    }
}