<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use Exception;
use App\Services\AuthService;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
            ], 201);
        } catch (Exception $e) {
            //Log::error("User registration failed");
        }
    }

    // User Login
    public function login(LoginUserRequest $request)
    {
        $token = $this->authService->login($request->validated());
        if(!$token){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
        ], 200);
    }
}
