<?php namespace PavelTopilin\Blog\Models;

use Model;

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
    public $rules = [
    ];
}
