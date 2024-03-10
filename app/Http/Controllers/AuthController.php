<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // register users
    public function register(Request $request)
    {

        $data = $request->validate([
            'user_type' => 'required|string|in:employer,candidate',
            'email' => 'required|email|string|unique:users,email',
            'field' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);

        if($request->user_type === 'employer'){
            $data += $request->validate([
                'company_name' => 'required|string',
                'company_size' => 'required|int',
        ]);

        } else {
            $data += $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
            ]);
        }

        /** @var \App\Models\User $user */
        $user = User::create([
            'user_type' => $data['user_type'],
            'field' => $data['field'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        if ($data['user_type'] === 'employer') {
            Employer::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'company_size' => $data['company_size']
            ]);
        } else {
            Candidate::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'user_id' => $user->id,
            ]);
        }

        $token = $user->createToken('main')->plainTextToken;

        return response()->json([
            'message' => 'Your account is created successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    //login users
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('main')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}