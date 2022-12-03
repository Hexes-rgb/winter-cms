<?php

namespace PavelTopilin\Blog\Components;

use Winter\User\Facades\Auth;
use Cms\Classes\ComponentBase;
use PavelTopilin\Blog\Models\Post;
use Illuminate\Support\Facades\Request;
use Winter\Storm\Support\Facades\Flash;
use Winter\Storm\Support\Facades\Input;
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
        $data = Request::all();
        // dd($data);
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
        $user = Auth::getUser();
        $post = Post::create([
            'title' => $data['title'],
            'text' => $data['text'],
            'user_id' => $user->id,
        ]);

        if ($data['photo'] ?? false) {

            $post->photo()->create(['data' => $data['photo']]);
        }
        Flash::success('Jobs done!');
        return Redirect::to('/post/' . $post->id . '/edit');
    }
}
