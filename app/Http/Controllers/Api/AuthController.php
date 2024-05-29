<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        // trying to find the user by it's email that wish to login using where clause, where the email is exactly as the request email which is field in request body and fetch the 1st element that can be found 
        $user = User::where('email', $request->email)->first();

        // when user doesnot exist
        if (!$user) {
            // this VE class has this static method to send message
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);
        }

        // hashing user input password and comparing the hash with hashed password stored in DB
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                // we don't pass in the error in password field because this can be used to verify if certain user exists inside the database and they might brute force to access that account
                'email' => ['The provided credential are incorrect']
            ]);
        }
        // generating tokens
        $token = $user->createToken('api-token')->plainTextToken; //now this token is stored in DB

        return response()->json([
            'token' => $token
        ]);

    }

    public function logout(Request $request)
    {

    }
}
