<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = ArticleCategory::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'content' => 'required|string',
            'indocontent' => 'required|string',
            'idcategory' => 'required|exists:article_category,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'publish' => 'required|boolean',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('articles', 'public');
        }

        Article::create([

            'heading' => $request->heading,
            'judul' => $request->judul,
            'content' => $request->content,
            'indocontent' => $request->indocontent,
            'idcategory' => $request->idcategory,
            'gambar' => $path,
            'publish' => $request->publish,
        ]);

        return redirect()->route('admin.articles.create')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $categories = ArticleCategory::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'content' => 'required|string',
            'indocontent' => 'required|string',
            'idcategory' => 'required|exists:article_category,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'publish' => 'required|boolean',
        ]);

        $path = $article->gambar;
        if ($request->hasFile('gambar')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('gambar')->store('articles', 'public');
        }

        $article->update([

            'heading' => $request->heading,
            'judul' => $request->judul,
            'content' => $request->content,
            'indocontent' => $request->indocontent,
            'idcategory' => $request->idcategory,
            'gambar' => $path,
            'publish' => $request->publish,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->gambar) {
            Storage::disk('public')->delete($article->gambar);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}
