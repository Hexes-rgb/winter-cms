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
use Illuminate\Support\Facades\Redirect;
use PavelTopilin\Blog\Exports\PostsExport;
use PavelTopilin\Blog\Classes\TagsDataTable;
use PavelTopilin\Blog\Classes\AuthorsDataTable;

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

    public function onRun()
    {
        $this->onFilter();
        $this->onGetAuthors();
        $this->onGetTags();
    }

    public function onGetAuthors()
    {
        $authorSearch = (empty(Request::only('authorSearch'))) ? Session::get('authorSearch') : Request::only('authorSearch');
        $authors = User::when(
            (empty($authorSearch['authorSearch'])) ? false : $authorSearch['authorSearch'],
            function ($query, $text) {
                $query->where('name', 'LIKE', '%' . $text . '%');
            }
        )->get();
        Session::put('authorSearch', $authorSearch);
        $this->page['authors'] = $authors;
        return response()->json(['partial' => $this->renderPartial('postTable::authors')]);
    }

    public function onGetTags()
    {
        $filtersMenu = (empty(Request::only('tagSearch'))) ? Session::get('tagSearch') : Request::only('tagSearch');
        $tags = Tag::when(
            (empty($filtersMenu['tagSearch'])) ? false : $filtersMenu['tagSearch'],
            function ($query, $text) {
                $query->where('name', 'LIKE', '%' . $text . '%');
            }
        )->get();
        $this->page['tags'] = $tags;
        Session::put('tagSearch', $filtersMenu);
        return response()->json(['partial' => $this->renderPartial('postTable::tags')]);
    }

    public function getFilters()
    {
        return (empty(Request::only('filters')['filters'])) ? Session::get('filters') : Request::only('filters')['filters'];
    }

    public function getPosts()
    {

        $filters = $this->getFilters();

        $posts = Post::postFilters($filters)->when(
            (empty($filters['sort'])) ? false : $filters['sort'],
            function ($query, $sort) {
                if (empty($sort[1])) {
                    return;
                }
                return $query->orderBy($sort[0], $sort[1]);
            }
        );
        Session::put('filters', $filters);

        return $posts;
    }

    public function onFilter()
    {
        $posts = $this->getPosts()->paginate(5);
        $this->page['posts'] = $posts;
        return response()->json(['partial' => $this->renderPartial('postTable::posts-table')]);
    }

    public function onExportPosts()
    {
        return Redirect::to('trigger-download');
    }

    public function onLoadChartsTables()
    {
        $filters = $this->getFilters();

        return [
            'authorsData' => (new AuthorsDataTable($filters))->build(),
            'tagsData' => (new TagsDataTable($filters))->build()
        ];
    }
}
