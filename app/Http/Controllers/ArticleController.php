<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->middleware('auth:sanctum')->except(['getAll', 'getById']); // Autentikasi untuk selain getAll dan getById
    }

    // Menampilkan semua artikel
    public function getAll()
    {
        $articles = $this->articleService->getAll();

        return Response::json([
            'articles' => $articles,
        ]);
    }

    // Menampilkan artikel berdasarkan ID
    public function getById($id)
    {
        $article = $this->articleService->getById($id);

        if ($article) {
            return Response::json(['article' => $article]);
        }

        return Response::json(['error' => 'Article not found'], 404);
    }

    // Menambahkan artikel baru
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:10240', // Validasi file gambar
        ]);
    
        try {
            // Cek jika file gambar ada dalam request
            $image = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
            }
    
            $article = $this->articleService->create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'user_id' => auth()->id(),
                'category_id' => $validatedData['category_id'],
                'image' => $image, // Kirim file gambar yang ada
            ]);
    
            return response()->json([
                'message' => 'Article created successfully',
                'article' => $article,
            ], 201);
        } catch (\Exception $e) {
            // Tangani jika ada error saat proses
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Mengupdate artikel
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:10240',
        ]);
    
        $article = $this->articleService->getById($id);
    
        if ($article && $article->user_id === auth()->id()) {
            try {
                $image = $request->file('image'); // Null kalau gak ada
    
                $updatedArticle = $this->articleService->update($id, [
                    'title' => $validatedData['title'],
                    'content' => $validatedData['content'],
                    'category_id' => $validatedData['category_id'] ?? null,
                    'image' => $image,
                ]);
    
                return response()->json([
                    'message' => 'Article updated successfully',
                    'article' => $updatedArticle,
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
    
        return response()->json(['error' => 'Unauthorized or Article not found'], 403);
    }
    
    
    // Menghapus artikel (soft delete)
    public function delete($id)
    {
        $article = $this->articleService->getById($id);

        // Pastikan artikel ditemukan dan hanya pemilik yang bisa menghapus
        if ($article && $article->user_id === auth()->id()) {
            try {
                $deleted = $this->articleService->delete($id);

                if ($deleted) {
                    return Response::json(['message' => 'Article deleted successfully']);
                }

                return Response::json(['error' => 'Article not found'], 404);
            } catch (\Exception $e) {
                return Response::json(['error' => $e->getMessage()], 400);
            }
        }

        return Response::json(['error' => 'Unauthorized or Article not found'], 403);
    }
}
