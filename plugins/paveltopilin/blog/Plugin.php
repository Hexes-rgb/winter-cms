<?php

namespace PavelTopilin\Blog;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            \PavelTopilin\Blog\Components\PostList::class       => 'postList',
            \PavelTopilin\Blog\Components\PostShow::class       => 'postShow',
        ];
    }

    public function registerSettings()
    {
    }
}
