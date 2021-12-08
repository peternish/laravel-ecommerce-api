<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', '=', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false
            ]);
        }

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
