<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    // Fungsi untuk mendapatkan semua kategori
    public function getAll()
    {
        return Category::with('articles')->get(); 
    }

    // Fungsi untuk mendapatkan kategori berdasarkan ID
    public function getById($id)
    {
        return Category::with('articles')->find($id); 
    }

    // Fungsi untuk membuat kategori baru
    public function create(array $data)
    {
        $uniqueId = $this->generateUniqueId();

        return Category::create([
            'id'          => $uniqueId,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'user_id'     => Auth::id() ?? session('auth_user_id'), // <- bagian penting! HATI-HATI SAMA SANCTUM
        ]);
    }

    // Fungsi untuk memperbarui kategori
    public function update($id, array $data)
    {
        $category = Category::find($id);
    
        if (!$category) return null;
    
        $currentUserId = $data['user_id'] ?? Auth::id();
    
        // Cek ownership
        if ($category->user_id !== $currentUserId) {
            throw new \Exception('You do not have permission to update this category.');
        }
    
        $category->update($data);
        return $category;
    }
    

    // Fungsi untuk menghapus kategori (soft delete)
    public function delete($id)
    {
        $category = Category::find($id);

        if (!$category) return false;

        if ($category->user_id !== (Auth::id() ?? session('auth_user_id'))) {
            throw new \Exception('You do not have permission to delete this category.');
        }        

        $category->delete();
        return true;
    }

    // Fungsi untuk menghasilkan ID unik 6 digit
    protected function generateUniqueId(): int
    {
        do {
            $uniqueId = random_int(100000, 999999);
        } while (Category::where('id', $uniqueId)->exists());

        return $uniqueId;
    }
}
