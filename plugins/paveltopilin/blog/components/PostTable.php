<?php

namespace PavelTopilin\Blog\Components;

use Winter\User\Models\User;
use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Tag;
use PavelTopilin\Blog\Models\Post;
use Winter\Storm\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class PostTable extends ComponentBase
{
    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'PostTable Component',
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

    public function prepareVars()
    {
        $this->page['authors'] = User::all();
        $this->page['tags'] = Tag::all();
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->onFilter();
    }

    public function onFilter()
    {
        $authors = DB::table('users')->pluck('id')->toArray();
        $tags = [];

        if (Session::get('filters') != []) {
            $filters = Session::pull('filters');
        }
        if (Request::except('page') != []) {
            $filters = Request::except('page');
        }

        if (isset($filters['authors'])) {
            $authors = $filters['authors'];
            $authors = array_map('intval', $authors);
        }
        if (isset($filters['tags'])) {
            $tags = $filters['tags'];
            $tags = array_map('intval', $tags);
        }

        $posts = Post::when($authors, function ($query, $authors) {
            return $query->whereIn('user_id', $authors);
        })->when($tags, function ($query, $tags) {
            return $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('id', $tags);
            });
        })
            ->paginate(3);
        $this->page['posts'] = $posts;
        Session::put('filters', $filters);
        return response()->json(['partial' => $this->renderPartial('postTable::posts-table')]);
    }
}
