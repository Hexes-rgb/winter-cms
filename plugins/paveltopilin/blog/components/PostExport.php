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
            ->when(
                (empty($filters['text'])) ? false : $filters['text'],
                function ($query, $text) {
                    return $query->where('paveltopilin_blog_posts.title', 'LIKE', '%' . $text . '%');
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
                    return $query->where('paveltopilin_blog_posts.created_at', '>', $afterCreated);
                }
            )->when(
                (empty($filters['beforeCreated'])) ? false : $filters['beforeCreated'],
                function ($query, $beforeCreated) {
                    return $query->where('paveltopilin_blog_posts.created_at', '<', $beforeCreated);
                }
            )->when(
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
