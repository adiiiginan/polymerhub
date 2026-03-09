<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('id.frontend.white-paper', compact('articles'));
    }

    public function indexen()
    {
        $articles = Article::latest()->paginate(10);
        return view('en.frontend.white-paper', compact('articles'));
    }
}
