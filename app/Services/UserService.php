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
        // Validasi sederhana, bisa ditambah aturan lebih ketat
        $this->validateRegisterData($data);

        // Generate ID unik 6 digit
        $uniqueId = $this->generateUniqueId();

        // Create user with unique ID
        return User::create([
            'id'       => $uniqueId,  // Menggunakan ID yang sudah digenerate
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Fungsi untuk login
    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        // Jika tidak ada user atau password tidak cocok
        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        // Membuat token baru untuk user
        $token = $user->createToken('YourAppName')->plainTextToken;

        return $token;
    }

    // Fungsi untuk logout
    public function logout($user)
    {
        // Hapus semua token user
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        Log::info("User {$user->id} logged out.");
        
        // Kembalikan status logout
        return true;
    }

    // Fungsi tambahan untuk validasi data registrasi
    protected function validateRegisterData(array $data)
    {
        // Misal, validasi email, password, dll.
        if (empty($data['email']) || empty($data['password'])) {
            throw new \Exception('Email and password are required');
        }

        // Bisa juga menambahkan pengecekan apakah email sudah terdaftar
        if (User::where('email', $data['email'])->exists()) {
            throw new \Exception('Email already taken');
        }
    }

    // Fungsi untuk generate ID unik 6 digit
    protected function generateUniqueId(): int
    {
        // Generate 6 digit ID acak
        do {
            $uniqueId = random_int(100000, 999999);  // ID antara 100000 dan 999999
        } while (User::where('id', $uniqueId)->exists());  // Pastikan ID belum ada

        return $uniqueId;
    }
}
