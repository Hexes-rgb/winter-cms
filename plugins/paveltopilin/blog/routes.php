<?php

use PavelTopilin\Blog\Models\Post;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Winter\Storm\Support\Facades\Input;
use PavelTopilin\Blog\Exports\PostsExport;

Route::any('/post', function () {
    dd(\Request::all());
});

Route::any('/post/export', function () {
    $filters = Session::get('filters');
    // dd(Session::all());
    $posts = Post::when(
        (empty($filters['text'])) ? false : $filters['text'],
        function ($query, $text) {
            return $query->where('title', 'LIKE', '%' . $text . '%');
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
            return $query->where('created_at', '>', $afterCreated);
        }
    )->when(
        (empty($filters['beforeCreated'])) ? false : $filters['beforeCreated'],
        function ($query, $beforeCreated) {
            return $query->where('created_at', '<', $beforeCreated);
        }
    )->when(
        (empty($filters['sort'])) ? false : $filters['sort'],
        function ($query, $sort) {
            if (empty($sort[1])) {
                return;
            }
            return $query->orderBy($sort[0], $sort[1]);
        }
    )->get();
    return Excel::download(new PostsExport($posts), 'posts.xlsx');
});
