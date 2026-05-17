<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $article_id
 * @property string $user_name
 * @property string $comment
 * @property bool $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article $article
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment find($id, $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment firstOrCreate(array $attributes = [], array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment create(array $attributes = [])
 */
class ArticleComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_name',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
