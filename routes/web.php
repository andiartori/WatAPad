<?php

use Illuminate\Support\Facades\Route;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-cloudinary', function () {
    try {
        $upload = Cloudinary::upload(public_path('a.jpg'));

        // Memastikan hasil upload
        if ($upload) {
            return response()->json([
                'secure_url' => $upload->getSecureUrl(),
                'public_id' => $upload->getPublicId(),
            ]);
        }

        // Jika upload gagal
        return response()->json(['error' => 'Upload failed.'], 500);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
