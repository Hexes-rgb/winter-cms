<?php

namespace PavelTopilin\Blog\Models;

use Model;

use Winter\User\Models\User;
use PavelTopilin\Blog\Models\Post;

/**
 * Model
 */
class Comment extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    use \Winter\Storm\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = ['text', 'user_id', 'post_id', 'comment_id'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'paveltopilin_blog_comments';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    /**
     * @var array Attribute names to encode and decode using JSON.
     */
    public $jsonable = [];

    public $belongsTo = [
        'author' => [User::class, 'key' => 'user_id', 'otherKey' => 'id'],
        'post' => [Post::class]
    ];

    public $hasMany = [
        'comments' => [Comment::class, 'key' => 'comment_id', 'otherKey' => 'id']
    ];
}
