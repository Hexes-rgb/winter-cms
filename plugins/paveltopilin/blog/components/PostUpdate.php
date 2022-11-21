<?php

namespace PavelTopilin\Blog\Components;

use Winter\User\Facades\Auth;
use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;
use Winter\Storm\Support\Facades\Flash;
use Winter\Storm\Support\Facades\Validator;
use Winter\Storm\Exception\ValidationException;

class PostUpdate extends ComponentBase
{
    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'postUpdate Component',
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

    public function onUpdatePost()
    {
        $data = post();
        $rules = [
            'title' => 'required|min:3|max:200',
            'text' => 'required|min:3',
        ];
        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        $data = $validation->validated();
        $title = $data['title'];
        $text = $data['text'];
        $post = Post::findOrFail(input('post_id'));
        $user = Auth::getUser();
        if ($user->id == $post->author->id) {
            $post->update([
                'title' => $title,
                'text' => $text,
            ]);
            Flash::success('Jobs done!');
        } else {
            Flash::error('You are not author');
        }
    }
}
