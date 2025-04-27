<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ArticleService;
use App\Models\Article;



class ArticlePageController extends Controller
{
    protected $categoryService;
    protected $articleService;

    public function __construct(CategoryService $categoryService, ArticleService $articleService)
    {
        $this->categoryService = $categoryService;
        $this->articleService = $articleService;
    }
//Create Article Page
    public function create()
    {
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login terlebih dahulu.');
        }

        $categories = $this->categoryService->getAll();

        return view('articles.create', compact('categories'));
    }

    //Create Article Page Execution
    public function store(Request $request)
    {
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login terlebih dahulu.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:10240', 
        ]);

        try {
            $image = $request->file('image');

            
            $this->articleService->create([
                'title' => $validatedData['title'],
                'overview' => $validatedData['overview'] ?? null,
                'content' => $validatedData['content'],
                'user_id' => session('auth_user_id'),
                'category_id' => $validatedData['category_id'],
                'image' => $image,
            ]);

            return redirect('/')->with('success', 'Artikel berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat artikel: ' . $e->getMessage());
        }
    }

    //Edit Page total 
    public function manage()
    {
        
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login dulu.');
        }
    
        $articles = Article::with('category') 
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->get();
    
        return view('articles.manage', compact('articles'));
    }


    // Edit Page perId
    public function edit($id)
    {
        // Ambil artikel berdasarkan ID
        $article = Article::findOrFail($id);
        $categories = $this->categoryService->getAll();

        return view('articles.edit', compact('article', 'categories'));
    }

    // MEdit Execution
    public function update(Request $request, $id)
    {
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login terlebih dahulu.');
        }
    
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'overview' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:10240', 
        ]);
    
        $article = Article::findOrFail($id);
    
        if ($article->user_id !== session('auth_user_id')) {
            return redirect('/')->with('error', 'Kamu tidak berhak mengupdate artikel ini.');
        }
    
        $image = $request->file('image');
    
        try {
            $this->articleService->update($id, [
                'title' => $validatedData['title'],
                'overview' => $validatedData['overview'],
                'content' => $validatedData['content'],
                'category_id' => $validatedData['category_id'],
                'image' => $image, 
            ]);
    
            return redirect()->route('articles.manage')->with('success', 'Artikel berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupdate artikel: ' . $e->getMessage());
        }
    }
    


    //Delete Execution 
public function deleteFromBlade($id)
{
    if (!session()->has('auth_token')) {
        return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
    }

    $article = Article::findOrFail($id);

    if ($article->user_id !== session('auth_user_id')) {
        return redirect()->route('articles.manage')->with('error', 'Kamu tidak berhak menghapus artikel ini.');
    }
    

    try {
        $this->articleService->delete($id);

        return redirect()->route('articles.manage')->with('success', 'Artikel berhasil dihapus.');
    } catch (\Exception $e) {
        return redirect()->route('articles.manage')->withErrors(['error' => $e->getMessage()]);
    }
}


    
}


