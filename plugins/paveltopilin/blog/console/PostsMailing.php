<?php

namespace PavelTopilin\Blog\Console;

use Winter\User\Models\User;
use Winter\Storm\Console\Command;
use PavelTopilin\Blog\Models\Post;
// use Illuminate\Support\Facades\Mail;
use Winter\Storm\Support\Facades\Mail;

class PostsMailing extends Command
{
    /**
     * @var string The console command name.
     */
    protected static $defaultName = 'blog:postsmailing';

    /**
     * @var string The name and signature of this command.
     */
    protected $signature = 'blog:postsmailing
        {--f|force : Force the operation to run and ignore production warnings and confirmation questions.}';

    /**
     * @var string The console command description.
     */
    protected $description = 'No description provided yet...';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $posts = Post::dailyPosts()->get();
        $users = User::dayAway()->get();
        $options = ['bcc' => true];
        Mail::sendTo($users, 'paveltopilin.blog::mail.posts-mailing', ['posts' => $posts], function ($message) {
            $message->from('us@example.com', 'Winter');
            $message->subject('Posts Mailing');
        }, $options);
    }

    /**
     * Provide autocomplete suggestions for the "myCustomArgument" argument
     */
    // public function suggestMyCustomArgumentValues(): array
    // {
    //     return ['value', 'another'];
    // }
}
