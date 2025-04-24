<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    // Fungsi untuk mendapatkan semua kategori
    public function getAll()
    {
        return Category::all();
    }

    // Fungsi untuk mendapatkan kategori berdasarkan ID
    public function getById($id)
    {
        return Category::find($id);
    }

    // Fungsi untuk membuat kategori baru
    public function create(array $data)
    {
        // Generate ID unik 6 digit
        $uniqueId = $this->generateUniqueId();

        return Category::create([
            'id'          => $uniqueId,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'user_id'     => Auth::id(), // Simpan ID user saat ini
        ]);
    }

    // Fungsi untuk memperbarui kategori
    public function update($id, array $data)
    {
        $category = Category::find($id);

        if (!$category) return null;

        // Cek ownership
        if ($category->user_id !== Auth::id()) {
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

        // Cek ownership
        if ($category->user_id !== Auth::id()) {
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
