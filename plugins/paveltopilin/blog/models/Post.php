<?php

namespace PavelTopilin\Blog\Models;

use Model;
use PavelTopilin\Blog\Models\Tag;

/**
 * Model
 */
class Post extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    use \Winter\Storm\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


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
        'tags' => [Tag::class, 'key' => 'tag_id', 'otherKey' => 'post_id', 'table' => 'paveltopilin_blog_post_tag']
    ];
}
