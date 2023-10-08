<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('custom.auth', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Invalid data need password and an valid adress mail', 'errors' => $errors], 401);
        }
        $credentials = $request->only('email', 'password');

        $minutes = 90 * 24 * 60;
        $token = Auth::setTTL($minutes)->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        $newToken = Auth::setTTL($minutes)->claims(['username' => $user->username, 'email' => $user->email])->fromUser($user);
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $newToken,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function register(Request $request){

        try {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();

            if (isset($errors['username'])) {
                return response()->json(['message' => 'Ce nom d\'utilisateur existe déjà'], 401);
            }

            if (isset($errors['email'])) {
                return response()->json(['message' => 'Cet email est déjà utilisé'], 401);
            }

            return response()->json(['message' => 'Validation failed', 'errors' => $errors], 401);
        }

        $user = User::create([
            'email' => $request->email,
            'roles' => 'user',
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User creation failed',
            ], 500);
        }
        $minutes = 90 * 24 * 60;
        $newToken = Auth::setTTL($minutes)->claims(['username' => $user->username, 'email' => $user->email])->fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $newToken,
                'type' => 'bearer',
            ]
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}