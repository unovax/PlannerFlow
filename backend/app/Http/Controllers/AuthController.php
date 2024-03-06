<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth()->user();

        $token = $user->createToken('token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request){
        $tenant = Tenant::create($request->all());
        $user_data = $request->user;
        $user_data['tenant_id'] = $tenant->id;
        $user_data['password'] = bcrypt($user_data['password']);
        $user = User::create($user_data);

        $token = $user->createToken('token')->plainTextToken;

        return response([
            'user' => $user->load('tenant'),
            'token' => $token
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response([
            'message' => 'Logged out successfully'
        ]);
    }
}
