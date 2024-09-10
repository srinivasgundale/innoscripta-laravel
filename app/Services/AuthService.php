<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(array $data): string
    {
        if (!Auth::attempt($data)) {
            return false;
        }

        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->accessToken;
        return $token;
    }
}
