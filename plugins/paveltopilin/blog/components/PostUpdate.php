<?php

namespace PavelTopilin\Blog\Components;

use Winter\User\Facades\Auth;
use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;
use Illuminate\Support\Facades\Request;
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
        $data = Request::all();
        $rules = [
            'title' => 'required|min:3|max:200',
            'text' => 'required|min:3',
            'photo' => 'image'
        ];
        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        $data = $validation->validated();
        $post = Post::findOrFail(input('post_id'));
        $user = Auth::getUser();
        if ($user->id == $post->author->id) {
            $post->update([
                'title' => $data['title'],
                'text' => $data['text'],
            ]);
            if ($data['photo'] ?? false) {
                $post->photo()->create(['data' => $data['photo']]);
            }
            Flash::success('Jobs done!');
        } else {
            Flash::error('You are not author');
        }
    }
}
