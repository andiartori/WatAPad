<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{
    // Fungsi untuk registrasi
    public function register(array $data): User
    {
        $this->validateRegisterData($data);

        // Generate ID unik 6 digit
        $uniqueId = $this->generateUniqueId();

        
        return User::create([
            'id'       => $uniqueId,  
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Fungsi untuk login
    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        $token = $user->createToken('YourAppName')->plainTextToken;

        return $token;
    }

    // Fungsi untuk logout
    public function logout($user)
    {
        if (!$user) {
            Log::warning("Logout attempted but user is null.");
            return false;
        }
    
        // Jika user menggunakan token-based (API), hapus semua token , GAK JADI PAKE SANCTUM ERROR
        if (method_exists($user, 'tokens')) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });
            Log::info("User {$user->id} tokens deleted.");
        }
    
        Log::info("User {$user->id} logged out.");
    
        return true;
    }
    

    // Fungsi tambahan untuk validasi data registrasi
    protected function validateRegisterData(array $data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            throw new \Exception('Email and password are required');
        }

        if (User::where('email', $data['email'])->exists()) {
            throw new \Exception('Email already taken');
        }
    }

    protected function generateUniqueId(): int
    {
        do {
            $uniqueId = random_int(100000, 999999); 
        } while (User::where('id', $uniqueId)->exists()); 

        return $uniqueId;
    }

    //fungsi untuk mendapatkan detail user
    public function getByEmail(string $email)
    {
    return User::where('email', $email)->firstOrFail();
    }
}
