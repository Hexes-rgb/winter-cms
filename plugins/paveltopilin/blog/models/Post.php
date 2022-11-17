<?php

namespace PavelTopilin\Blog\Models;

use Model;
use Winter\User\Models\User;
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
}
