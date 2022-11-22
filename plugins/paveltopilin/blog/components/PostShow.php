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
use Illuminate\Support\Facades\Redirect;
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

    public function prepareVars()
    {
        $post_id = $this->param('post_id');
        $post = Post::findOrFail($post_id);
        $this->page['post'] = $post;
        if (Auth::getUser()) {
            $this->page['canEdit'] = $post->author->id == Auth::getUser()->id;
            $this->page['canLike'] = true;
        } else {
            $this->page['canEdit'] = false;
            $this->page['canLike'] = false;
        }
    }

    public function onRun()
    {
        $this->prepareVars();
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
        $this->prepareVars();
    }

    public function onCreateComment()
    {
        $data = post();
        $rules = [
            'text' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            // throw new ValidationException($validator);
            return response()->json(['errors' => $validator->errors()]);
        }
        $data = $validator->validated();
        $text = $data['text'];
        $user = Auth::getUser();
        $comment = Comment::create([
            'text' => $text,
            'user_id' => $user->id,
            'post_id' => input('post_id'),
            'comment_id' => input('comment_id')
        ]);
        Flash::success('You did it!');
        $this->page['comment'] = $comment;
        return response()->json(['partial' => $this->renderPartial('postShow::comment')]);
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
