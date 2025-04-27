<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; 



class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        // Hanya getAll dan getById yang bisa diakses publik, lainnya butuh token
        
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
    public function store(Request $request)
    {
        // Kalau dari Blade: pakai session
        if (!$request->expectsJson() && !session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }
    
        // Kalau dari Postman: pakai Auth::id() dari Sanctum token
        if ($request->expectsJson() && !Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        try {
            if ($request->expectsJson()) {
                // Dari Postman/API (pakai Sanctum)
                $validatedData['user_id'] = Auth::id();
            } else {
                // Dari Blade Form (pakai session)
                $validatedData['user_id'] = session('auth_user_id');
            }
    
            $category = $this->categoryService->create($validatedData);
    
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Category created successfully',
                    'category' => $category,
                ], 201);
            }
    
            return redirect('/categories/create')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
    
            return back()->withErrors(['error' => $e->getMessage()]);
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

    //Fetching Server editpage untuk category
    public function editPage()
    {
        if (!session()->has('auth_token')) {
            return redirect('/login');
        }
    
        $categories = $this->categoryService->getAll();
    
        return view('categories.edit', compact('categories'));
    }
    
    //Fetching Server editpage untuk category PER ID
    public function editForm($id)
{
    if (!session()->has('auth_token')) {
        return redirect('/login');
    }

    $category = $this->categoryService->getById($id);

    if (!$category) {
        return redirect('/categories/edit')->withErrors(['error' => 'Kategori tidak ditemukan.']);
    }

    return view('categories.edit-form', compact('category'));
}

//endpoint untuk eksekusi FORM dari BLADE (NOTE PENTING SUPAYA INGAT : untuk create fungsi ini disatukan. tapi logicnya agak sulit di tracking  kalau error)
public function updateFromBlade(Request $request, $id)
{
    if (!session()->has('auth_token')) {
        return redirect('/login');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    try {
        // Tambahkan user_id dari session untuk kebutuhan service
        $validated['user_id'] = session('auth_user_id');

        $this->categoryService->update($id, $validated);
        return redirect('/categories/edit')->with('success', 'Kategori berhasil diperbarui.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function deleteFromBlade($id)
{
    if (!session()->has('auth_token')) {
        return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
    }

    try {
        $this->categoryService->delete($id);

        return redirect()->route('categories.edit.page')->with('success', 'Kategori berhasil dihapus.');
    } catch (\Exception $e) {
        return redirect()->route('categories.edit.page')->withErrors(['error' => $e->getMessage()]);
    }
}



}
