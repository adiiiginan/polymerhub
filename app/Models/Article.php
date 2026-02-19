<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'article';

    protected $fillable = [
        'slug',
        'heading',
        'judul',
        'content',
        'indocontent',
        'idcategory',
        'gambar',
        'publish',
    ];

    /**
     * Get the category that owns the article.
     */

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'idcategory');
    }


    /**
     * Get the author that owns the article.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
