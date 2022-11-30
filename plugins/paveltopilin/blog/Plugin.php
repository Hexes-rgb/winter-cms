<?php

namespace PavelTopilin\Blog;

use PavelTopilin\Blog\Console\PostsMailing;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            \PavelTopilin\Blog\Components\PostList::class       => 'postList',
            \PavelTopilin\Blog\Components\PostShow::class       => 'postShow',
            \PavelTopilin\Blog\Components\PostCreate::class       => 'postCreate',
            \PavelTopilin\Blog\Components\PostUpdate::class       => 'postUpdate',
            \PavelTopilin\Blog\Components\PostTable::class       => 'postTable',
            \PavelTopilin\Blog\Components\PostExport::class       => 'postExport',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMailTemplates()
    {
        return [
            'posts-mailing' => 'paveltopilin.blog::mail.posts-mailing',
        ];
    }

    public function register()
    {
        $this->registerConsoleCommand('paveltopilin.postsmailing', \PavelTopilin\Blog\Console\PostsMailing::class);
    }

    public function registerSchedule($schedule)
    {
        // $schedule->command(PostsMailing::class)->dailyAt('11:19');
    }
}
