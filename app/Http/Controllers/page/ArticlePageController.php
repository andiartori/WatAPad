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

    public function create()
    {
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login terlebih dahulu.');
        }

        // Ambil kategori langsung dari service, tidak lewat API
        $categories = $this->categoryService->getAll();

        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login terlebih dahulu.');
        }

        // Validasi input form
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:10240', // max 10MB
        ]);

        try {
            $image = $request->file('image');

            // Kirim ke service untuk disimpan
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

    public function manage()
    {
        // Cek apakah user sudah login berdasarkan session token
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Kamu harus login dulu.');
        }
    
        // Ambil data artikel langsung dari database (bukan lewat API)
        $articles = Article::with('category') // kalau ada relasi kategori
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->get();
    
        return view('articles.manage', compact('articles'));
    }


    // Method untuk menampilkan form edit
    public function edit($id)
    {
        // Ambil artikel berdasarkan ID
        $article = Article::findOrFail($id);
        $categories = $this->categoryService->getAll();

        return view('articles.edit', compact('article', 'categories'));
    }

    // Method untuk update artikel
    public function update(Request $request, $id)
{
    if (!session()->has('auth_token')) {
        return redirect('/login')->with('error', 'Kamu harus login terlebih dahulu.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'overview' => 'required|string',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:2048',
    ]);

    $article = Article::findOrFail($id);

    if ($article->user_id !== session('auth_user_id')) {
        return redirect('/')->with('error', 'Kamu tidak berhak mengupdate artikel ini.');
    }
    

    $article->title = $request->title;
    $article->overview = $request->overview;
    $article->content = $request->content;
    $article->category_id = $request->category_id;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('public/articles');
        $article->image_url = basename($imagePath);
    }

    $article->save();

    return redirect()->route('articles.manage')->with('success', 'Artikel berhasil diperbarui.');
}


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


