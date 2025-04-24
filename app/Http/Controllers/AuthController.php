<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Fungsi untuk registrasi
    public function register(Request $request)
    {
        // Validasi input request
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = $this->userService->register($validatedData);

            // Respon sukses dengan status 201 Created
            return Response::json([
                'message' => 'User registered successfully',
                'user'    => $user,
            ], 201);

        } catch (\Exception $e) {
            // Menangani error
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    // Fungsi untuk login
    public function login(Request $request)
    {
        // Validasi input request
        $validatedData = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $token = $this->userService->login($validatedData['email'], $validatedData['password']);

            // Respon sukses dengan token
            return Response::json([
                'message' => 'Login successful',
                'token'   => $token,
            ]);

        } catch (\Exception $e) {
            // Menangani error
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    // Fungsi untuk logout
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $this->userService->logout($user);

            // Respon sukses
            return Response::json(['message' => 'Logout successful'], 200);

        } catch (\Exception $e) {
            // Menangani error
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }
}
