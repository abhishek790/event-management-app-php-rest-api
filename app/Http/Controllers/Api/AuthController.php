<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credential are incorrect']
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credential are incorrect']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    // user has to be first signed to logout so it needs auth-sanctum middleware
    public function logout(Request $request)
    {
        //deletes  the tokens of the specific user form the personal_access_token table which are used to authenticate user
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);

        // to invilidate token just set time in sanctum.php file where you set "expiration"=> 60 *24 (1day)
    }
}
