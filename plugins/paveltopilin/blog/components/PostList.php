<?php

namespace PavelTopilin\Blog\Components;

use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;

class PostList extends ComponentBase
{
    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'postList Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties()
    {
        return [];
    }

    public function posts()
    {
        return Post::paginate(2);
    }
}
