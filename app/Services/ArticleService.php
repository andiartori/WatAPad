<?php

namespace App\Services;

use App\Models\Article;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Support\Collection;


class ArticleService
{
    // Fungsi untuk mendapatkan semua artikel
    public function getAll(): Collection
    {
        return Article::with('category')
            ->orderBy('created_at', 'desc') // ambil yang terbaru dulu
            ->get();
    }

    // Fungsi untuk mendapatkan artikel berdasarkan ID
    public function getById($id)
    {
        return Article::with(['category', 'user'])->find($id);
    }

    // Fungsi untuk mendapatkan artikel berdasarkan category_id
    public function getByCategoryId($categoryId): Collection
    {
    return Article::with('category')
        ->where('category_id', $categoryId)
        ->get();
    }

    // Fungsi untuk membuat artikel baru
    public function create(array $data)
    {
        // Generate ID unik 6 digit
        $uniqueId = $this->generateUniqueId();

        // Jika ada gambar, upload ke Cloudinary
        $imageUrl = null;
        if (isset($data['image']) && $data['image']) {
            $imageUrl = $this->uploadImageToCloudinary($data['image']);
        }

        // Membuat artikel baru dengan uniqueId dan menyertakan gambar jika ada
        return Article::create([
            'id'          => $uniqueId,
            'title'       => $data['title'],
            'overview'    => $data['overview'] ?? null, // Tambahan ini
            'content'     => $data['content'],
            'user_id'     => $data['user_id'],
            'category_id' => $data['category_id'] ?? null,
            'image_url'   => $imageUrl,
        ]);
        
    }

    // Fungsi untuk memperbarui artikel
    public function update($id, array $data)
    {
        $article = Article::find($id);
    
        if ($article) {
            // Upload image jika ada
            if (!empty($data['image'])) {
                $data['image_url'] = $this->uploadImageToCloudinary($data['image']);
            }
    
            // Hapus elemen yang tidak ada di kolom database
            unset($data['image']); // Eloquent gak tahu soal field ini
    
            $article->update([
                'title'       => $data['title'],
                'overview'    => $data['overview'] ?? $article->overview, // Tambahan ini
                'content'     => $data['content'],
                'category_id' => $data['category_id'] ?? $article->category_id,
                'image_url'   => $data['image_url'] ?? $article->image_url,
            ]);
            
    
            return $article;
        }
    
        return null;
    }
    

    // Fungsi untuk menghapus artikel (soft delete)
    public function delete($id)
    {
        $article = Article::find($id);

        if ($article) {
            $article->delete();
            return true;
        }

        return false;
    }

    // Fungsi untuk menghasilkan ID unik 6 digit
    protected function generateUniqueId(): int
    {
        // Generate ID acak 6 digit
        do {
            $uniqueId = random_int(100000, 999999);
        } while (Article::where('id', $uniqueId)->exists()); // Pastikan ID belum ada

        return $uniqueId;
    }

    // Fungsi untuk mengupload gambar ke Cloudinary
    protected function uploadImageToCloudinary($image)
    {
        try {
            if (!$image) {
                throw new \Exception('No image provided.');
            }
    
            \Log::info('Image original name: ' . $image->getClientOriginalName());
            \Log::info('Image path: ' . $image->getRealPath());
    
            // Upload menggunakan UploadApi langsung (lebih eksplisit)
            $uploadApi = new UploadApi();
            $response = $uploadApi->upload($image->getRealPath(), [
                'folder' => 'articles/',
                'resource_type' => 'image', // pastikan tipe resource
            ]);
    
            \Log::info('Cloudinary response:', $response->getArrayCopy());
    
            if (isset($response['secure_url'])) {
                return $response['secure_url'];
            }
    
            throw new \Exception('Image upload failed, no secure_url returned.');
        } catch (ApiError $e) {
            \Log::error('Cloudinary API error: ' . $e->getMessage());
            throw new \Exception('Cloudinary API error: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Cloudinary error: ' . $e->getMessage());
            throw new \Exception('Cloudinary upload failed: ' . $e->getMessage());
        }
    }
    
    
    
    
    
    
    
    
}
