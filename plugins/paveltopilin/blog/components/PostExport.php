<?php

namespace PavelTopilin\Blog\Components;

use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use PavelTopilin\Blog\Exports\PostsExport;

class PostExport extends ComponentBase
{
    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'postExport Component',
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
        $filters = Session::get('filters');

        $posts = Post::select(
            'paveltopilin_blog_posts.id',
            'paveltopilin_blog_posts.title',
            'paveltopilin_blog_posts.text',
            'users.name',
            'paveltopilin_blog_posts.created_at',
            'paveltopilin_blog_posts.updated_at'
        )
            ->join('users', 'users.id', '=', 'paveltopilin_blog_posts.user_id')
            ->postFilters($filters)->when(
                (empty($filters['sort'])) ? false : $filters['sort'],
                function ($query, $sort) {
                    if (empty($sort[1])) {
                        return;
                    }
                    return $query->orderBy($sort[0], $sort[1]);
                }
            );

        return (new PostsExport($posts))->download('posts.xlsx');
    }
}
