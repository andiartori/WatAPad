<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        // Hanya getAll dan getById yang bisa diakses publik, lainnya butuh token
        $this->middleware('auth:sanctum')->except(['getAll', 'getById']);
    }

    // Menampilkan semua kategori
    public function getAll()
    {
        $categories = $this->categoryService->getAll();

        return Response::json([
            'categories' => $categories,
        ]);
    }

    // Menampilkan kategori berdasarkan ID
    public function getById($id)
    {
        $category = $this->categoryService->getById($id);

        if ($category) {
            return Response::json(['category' => $category]);
        }

        return Response::json(['error' => 'Category not found'], 404);
    }

    // Menambahkan kategori baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category = $this->categoryService->create($validatedData);

            return Response::json([
                'message' => 'Category created successfully',
                'category' => $category,
            ], 201);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    // Memperbarui kategori
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category = $this->categoryService->update($id, $validatedData);

            if ($category) {
                return Response::json([
                    'message' => 'Category updated successfully',
                    'category' => $category,
                ]);
            }

            return Response::json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'You do not have permission to update this category.') {
                return Response::json(['error' => 'Forbidden: You are not the owner of this category'], 403);
            }

            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    // Menghapus kategori (soft delete)
    public function delete($id)
    {
        try {
            $deleted = $this->categoryService->delete($id);

            if ($deleted) {
                return Response::json(['message' => 'Category deleted successfully']);
            }

            return Response::json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'You do not have permission to delete this category.') {
                return Response::json(['error' => 'Forbidden: You are not the owner of this category'], 403);
            }

            return Response::json(['error' => $e->getMessage()], 400);
        }
    }
}
