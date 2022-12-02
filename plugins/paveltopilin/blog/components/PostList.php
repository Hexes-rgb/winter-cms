<?php

namespace PavelTopilin\Blog\Components;

use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;
use Illuminate\Support\Facades\Request;

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

    public function onRun()
    {
        $user_id = $this->param('user_id') ?? false;
        $posts = Post::when($user_id, function ($query, $user_id) {
            return $query->where('user_id', $user_id);
        })->paginate(2);
        $this->page['posts'] = $posts;
    }
}
