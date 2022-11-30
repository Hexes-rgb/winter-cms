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
