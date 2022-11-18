<?php

namespace PavelTopilin\Blog\Components;

use Carbon\Carbon;
use Winter\User\Models\User;
use Winter\User\Facades\Auth;
use Cms\Classes\ComponentBase;
use Backend\Facades\BackendAuth;
use PavelTopilin\Blog\Models\Post;
use PavelTopilin\Blog\Models\Comment;
use Winter\Storm\Support\Facades\Flash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Winter\Storm\Exception\ValidationException;

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
        $this->page['post'] = Post::findOrFail($post_id);
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

    public function onCreateComment()
    {
        // $data = post();
        // $rules = [
        //     'text' => 'required',
        // ];
        // $validation = Validator::make($data, $rules);
        // if ($validation->fails()) {
        //     $this->page['errors'] = $validation->errors();
        // }
        // $data = $validation->validated();
        $text = input('text');
        $user = Auth::getUser();
        $comment = Comment::create([
            'text' => $text,
            'user_id' => $user->id,
            'post_id' => input('post_id'),
            'comment_id' => input('comment_id')
        ]);
        $this->page['comment'] = $comment;
        Flash::success('You did it!');
    }

    public function onDeleteComment()
    {
        $comment = Comment::findOrFail(input('comment_id'));
        $comment->delete();
        $this->page['comment'] = $comment;
        Flash::success('Comment has been deleted');
    }

    public function onRestoreComment()
    {
        $comment = Comment::withTrashed()->find(input('comment_id'));
        $comment->restore();
        $this->page['comment'] = $comment;
        Flash::success('Comment has been restored');
    }
}
