<?php

namespace PavelTopilin\Blog\Models;

use Model;
use Carbon\Carbon;
use System\Models\File;
use Winter\User\Models\User;
use Winter\User\Facades\Auth;
use PavelTopilin\Blog\Models\Tag;
use PavelTopilin\Blog\Models\Comment;

/**
 * Model
 */
class Post extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    use \Winter\Storm\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'text', 'user_id'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'paveltopilin_blog_posts';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    public $attachMany = [
        'photo' => File::class
    ];

    /**
     * @var array Attribute names to encode and decode using JSON.
     */
    public $jsonable = [];

    public $belongsToMany = [
        'tags' => [Tag::class, 'key' => 'post_id', 'otherKey' => 'tag_id', 'table' => 'paveltopilin_blog_post_tag'],
        'viewes' => [User::class, 'table' => 'paveltopilin_blog_views', 'timestamps' => true],
        'likes' => [User::class, 'table' => 'paveltopilin_blog_likes', 'timestamps' => true, 'pivot' => ['deleted_at']]
    ];

    public $belongsTo = [
        'author' => [User::class, 'key' => 'user_id', 'otherKey' => 'id']
    ];

    public $hasMany = [
        'comments' => [Comment::class, 'key' => 'post_id', 'otherKey' => 'id', 'conditions' => 'comment_id is null']
    ];

    public function getLikesCount()
    {
        return $this->loadCount(['likes' => function ($query) {
            $query->where('paveltopilin_blog_likes.deleted_at', null);
        }])->likes_count;
    }

    public function scopeDailyPosts($query)
    {
        $query->where('created_at', '>', Carbon::now()->subDays(1));
    }

    public function scopeFilterTags($query, $tags)
    {
        return $query->whereHas('tags', function ($q) use ($tags) {
            $q->withoutGlobalScope(NestedTreeScope::class)->whereIn('id', $tags);
        });
    }

    public function scopePostFilters($query, $filters)
    {
        return $query->when(
            (empty($filters['text'])) ? false : $filters['text'],
            function ($query, $text) {
                return $query->where('title', 'LIKE', '%' . $text . '%');
            }
        )->when(
            (empty($filters['authors'])) ? false : $filters['authors'],
            function ($query, $authors) {
                return $query->whereIn('user_id', $authors);
            }
        )->when(
            (empty($filters['tags'])) ? false : $filters['tags'],
            function ($query, $tags) {
                return $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            }
        )->when(
            (empty($filters['afterCreated'])) ? false : $filters['afterCreated'],
            function ($query, $afterCreated) {
                return $query->where('created_at', '>', $afterCreated);
            }
        )->when(
            (empty($filters['beforeCreated'])) ? false : $filters['beforeCreated'],
            function ($query, $beforeCreated) {
                return $query->where('created_at', '<', $beforeCreated);
            }
        );
    }
}
