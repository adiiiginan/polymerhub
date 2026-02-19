<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\ArticleCategory;

class FrontendController extends Controller
{
    public function whitePaper()
    {
        $category = ArticleCategory::where('category', 'White Paper')->first();
        $articles = $category ? $category->articles()->where('publish', 1)->latest()->paginate(12) : collect();

        return view('frontend.white-paper', compact('articles', 'category'));
    }

    public function brochure()
    {
        return view('frontend.brochure');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function show(Article $article)
    {
        return view('frontend.article.show', compact('article'));
    }
}
