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
    
            // Cek apakah request datang dari API endPOint(PENTING)
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'User registered successfully',
                    'user'    => $user,
                ], 201);
            }
    
            // Jika bukan JSON, berarti request dari Blade
            return redirect()->back()->with('success', 'Register berhasil!');
    
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
    
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    // Fungsi untuk login
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
    
        try {
            $token = $this->userService->login($validatedData['email'], $validatedData['password']);

            $user = $this->userService->getByEmail($validatedData['email']);

    
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Login successful',
                    'token'   => $token,
                    'auth_name' => $user->name, 
                    'user_id'    => $user->id, 

                ]);
            }
    
            session([
                'auth_token'    => $token,
                'auth_name'     => $user->name,
                'auth_user_id'  => $user->id, 
            ]);

            return redirect('/')->with('success', 'Login berhasil!');
    
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
    
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
    
    // Fungsi untuk logout
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
    
            $this->userService->logout($user);
    
            // Hapus session token & name
            session()->forget(['auth_token', 'auth_name']);
    
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Logout successful'], 200);
            }
    
            return redirect('/login')->with('success', 'Logout berhasil!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
    
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    
}
