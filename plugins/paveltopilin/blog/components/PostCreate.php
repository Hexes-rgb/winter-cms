<?php

namespace PavelTopilin\Blog\Components;

use Winter\User\Facades\Auth;
use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;
use Winter\Storm\Support\Facades\Flash;
use Illuminate\Support\Facades\Redirect;
use Winter\Storm\Support\Facades\Validator;
use Winter\Storm\Exception\ValidationException;

class PostCreate extends ComponentBase
{
    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'postRedactor Component',
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

    public function onCreatePost()
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
        $user = Auth::getUser();
        $post = Post::create([
            'title' => $title,
            'text' => $text,
            'user_id' => $user->id,
        ]);
        return Redirect::to('/post/' . $post->id . '/edit');
    }
}
