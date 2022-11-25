<?php

namespace PavelTopilin\Blog\Components;

use Carbon\Carbon;
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
        $text = "";
        $afterCreated = Carbon::createFromTimestamp(0)->toDateString();
        $beforeCreated = Carbon::create(2038, 1, 19, 3, 14, 7);
        $sort = ['id', 'asc'];
        $authorsSort = ['name', 'asc'];
        $filters = array();

        // dd(Request::all());
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
        if (isset($filters['query'])) {
            $text = $filters['query'];
        }
        if (isset($filters['afterCreated'])) {
            $afterCreated = $filters['afterCreated'];
        }
        if (isset($filters['beforeCreated'])) {
            $beforeCreated = $filters['beforeCreated'];
        }
        if (isset($filters['sort'])) {
            $sort = $filters['sort'];
            if ($sort[0] == 'author') {
                $authorsSort = ['name', $sort[1]];
                $sort = null;
            } else {
                if ($sort[1] == "") {
                    $sort = null;
                }
            }
        }

        $posts = Post::when($text, function ($query, $text) {
                return $query->where('title', 'LIKE', '%' . $text . '%');
            })->when($authors, function ($query, $authors) {
                return $query->whereIn('user_id', $authors);
            })->when($tags, function ($query, $tags) {
                return $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            })->when($afterCreated, function ($query, $afterCreated) {
                return $query->where('created_at', '>', $afterCreated);
            })->when($beforeCreated, function ($query, $beforeCreated) {
                return $query->where('created_at', '<', $beforeCreated);
            })->when($sort, function ($query, $sort) {
                return $query->orderBy($sort[0], $sort[1]);
            })
            // ->when($authorsSort, function ($query, $authorsSort) {
            //     return $query->orderBy('name', $authorsSort[1]);
            // })
            // ->toSql();
            ->paginate(3);
        // ->get();
        $this->page['posts'] = $posts;
        Session::put('filters', $filters);
        return response()->json(['partial' => $this->renderPartial('postTable::posts-table')]);
    }
}
