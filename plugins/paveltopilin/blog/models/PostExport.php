<?php

namespace PavelTopilin\Blog\Models;

use PavelTopilin\Blog\Models\Post;

class PostExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $posts = Post::all();
        $posts->each(function ($post) use ($columns) {
            $post->addVisible($columns);
        });
        return $posts->toArray();
    }
}
