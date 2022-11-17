<?php

namespace PavelTopilin\Blog\Components;

use Carbon\Carbon;
use Winter\User\Models\User;
use Winter\User\Facades\Auth;
use Cms\Classes\ComponentBase;
use Backend\Facades\BackendAuth;
use PavelTopilin\Blog\Models\Post;

class PostShow extends ComponentBase
{
    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'post Component',
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
        $post_id = $this->param('post_id');
        $this->page['post'] =  Post::findOrFail($post_id);
    }

    public function onLike()
    {
        $post = Post::findOrFail(input('post_id'));
        $user = Auth::getUser();
        $likeIsExisted = $post->likes->where('id', $user->id)->isNotEmpty();
        if (!$likeIsExisted) {
            $post->likes()->attach($user);
        } elseif ($likeIsExisted and $likeIsDeleted = ($post->likes->find($user->id)->pivot->deleted_at != null)) {
            $post->likes->find($user->id)->pivot->deleted_at = null;
            $post->likes->find($user->id)->pivot->save();
        } else {
            $post->likes->find($user->id)->pivot->deleted_at = Carbon::now();
            $post->likes->find($user->id)->pivot->save();
        }
        $post->load('likes');
        $this->page['post'] = $post;
    }
}
